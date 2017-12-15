<?php

namespace AppBundle\Command;

use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Input\InputOption;
use Doctrine\Bundle\DoctrineBundle\Command\DoctrineCommand;
use AppExtraBundle\Service\AdminUploadManager;
use AppBundle\Entity\Brand;
use AppBundle\Entity\Category;
use AppBundle\Entity\Product;
use AppBundle\Entity\ProductPicture;
use AppBundle\Entity\ProductFile;

/**
 * Class OldCatalogImportCommand
 */
class OldCatalogImportCommand extends DoctrineCommand
{
    /**
     * @var AdminUploadManager
     */
    protected $uploadManager;

    /**
     * OldCatalogImportCommand constructor
     *
     * @param AdminUploadManager $uploadManager
     */
    public function __construct(AdminUploadManager $uploadManager)
    {
        parent::__construct();

        $this->uploadManager = $uploadManager;
    }

    /**
     * {@inheritdoc}
     */
    protected function configure()
    {
        $this->setName('app:old:catalog:import');
    }


    /**
     * {@inheritdoc}
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $entityManager = $this->getEntityManager('default');
        $importDir = $this->getContainer()->get('kernel')->getProjectDir() . '/var/';

        $brandMap = [];
        $brandList = $entityManager->getRepository(Brand::class)->findAll();
        foreach ($brandList as $brand) {
            $brandMap[$brand->getTitle()] = $brand;
        }

        $categoryMap = [];
        $categoryList = $entityManager->getRepository(Category::class)->findAll();
        foreach ($categoryList as $category) {
            $categoryMap[$category->getTitle()] = $category;
        }

        $productList = json_decode(file_get_contents($importDir . 'products.json'), true);
        $productPictureList = json_decode(file_get_contents($importDir . 'product_pictures.json'), true);

        $productPictureMap = [];
        foreach ($productPictureList[2]['data'] as $productPictureData) {
            $productPictureMap[$productPictureData['product_id']][] = $productPictureData;
        }

        $pictureSort = [];
        foreach ($productList[2]['data'] as $productData) {

            if (!isset($categoryMap[$productData['category_name']])) {
                continue;
            }

            if (!isset($brandMap[$productData['brand_name']])) {
                $brand = (new Brand())
                    ->setTitle($productData['brand_name'])
                    ->setCountry($productData['brand_country']);
                $entityManager->persist($brand);

                $brandMap[$productData['brand_name']] = $brand;
            }

            $product = (new Product())
                ->setCategory(
                    $categoryMap[$productData['category_name']]
                )
                ->setBrand(
                    $brandMap[$productData['brand_name']]
                )
                ->setTitle($productData['product_name'])
                ->setPrice($productData['product_price'])
                ->setDescription($productData['product_full_desc'])
                ->setActive($productData['product_active']);
            $entityManager->persist($product);

            $pictureUrl = '';
            if ($productData['product_picture_big']) {
                $pictureUrl = 'http://bodysolid.ru/images/products/big/' . $productData['product_picture_big'];
            } elseif ($productData['product_picture'])  {
                $pictureUrl = 'http://bodysolid.ru/images/products/' . $productData['product_picture'];
            }

            if ($pictureUrl) {
                $picturePath = $this->upload($pictureUrl, ProductPicture::class, 'image');
                $productPicture = (new ProductPicture())
                    ->setImage($picturePath)
                    ->setProduct($product)
                    ->setSort(
                        @ ++$pictureSort[$productData['product_id']] * 10
                    );
                $entityManager->persist($productPicture);
            }

            if (isset($productPictureMap[$productData['product_id']])) {
                foreach ($productPictureMap[$productData['product_id']] as $productPictureData) {
                    $pictureUrl = 'http://bodysolid.ru/images/products/additional/big/' . $productPictureData['picture_name_big'];
                    $picturePath = $this->upload($pictureUrl, ProductPicture::class, 'image');
                    $productPicture = (new ProductPicture())
                        ->setImage($picturePath)
                        ->setProduct($product)
                        ->setSort(
                            @ ++$pictureSort[$productData['product_id']] * 10
                        );
                    $entityManager->persist($productPicture);
                }
            }
        }

        $entityManager->flush();
    }

    /**
     * {@inheritdoc}
     */
    protected function executeCategories(InputInterface $input, OutputInterface $output)
    {
        $entityManager = $this->getEntityManager('default');
        $importDir = $this->getContainer()->get('kernel')->getProjectDir() . '/var/';

        $parentCategoryMap = [5 => 3, 13 => 4, 20 => 5, 22 => 6, 7 => 7];
        
        $categoryMap = [];
        $categoryList = $entityManager->getRepository(Category::class)->findAll();
        foreach ($categoryList as $category) {
            $categoryMap[$category->getId()] = $category;
        }

        $categoryList = json_decode(file_get_contents($importDir . 'categories.json'), true);

        $categorySort = [];
        foreach ($categoryList[2]['data'] as $categoryData) {
            $category = (new Category())
                ->setCategory($categoryMap[$parentCategoryMap[$categoryData['group_id']]])
                ->setTitle($categoryData['category_name'])
                ->setShortTitle($categoryData['category_short_name'])
                ->setSort(
                    @ ++$categorySort[$categoryData['group_id']] * 10
                )
            ->setActive($categoryData['category_active']);
            $entityManager->persist($category);
        }

        $entityManager->flush();
    }

    /**
     * @param string $url
     * @param string $class
     * @param string $field
     * @return string
     */
    protected function upload(string $url, string $class, string $field)
    {
        $fieldDesc = $this->uploadManager->getFields($class)[$field];

        $name = pathinfo($url, PATHINFO_BASENAME);
        $path = $fieldDesc['directory'] . DIRECTORY_SEPARATOR . $name;
        if (!file_exists($path)) {
            $data = @file_get_contents(str_replace(' ', '%20', $url));
            if ($data) {
                @file_put_contents($path, $data);
            }
        }

        return $this->uploadManager->getFilePath($name, $fieldDesc['alias']);
    }
}