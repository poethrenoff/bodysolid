<?php

namespace AppBundle\Controller;

use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use AppBundle\Service\PriceManager;
use AppBundle\Entity\Category;
use AppBundle\Entity\Product;

class ProductController extends Controller
{
    /**
     * @var PriceManager
     */
    protected $priceManager;

    /**
     * CartController constructor
     *
     * @param PriceManager $priceManager
     */
    public function __construct(PriceManager $priceManager)
    {
        $this->priceManager = $priceManager;
    }

    /**
     * Category menu
     *
     * @param Request $request
     * @return Response
     */
    public function menuAction(Request $request)
    {
        $categoryName = $request->get('categoryName');
        $currentCategory = $categoryName ? $this->getDoctrine()->getManager()
            ->getRepository(Category::class)->findOneBy(['active' => true, 'name' => $categoryName]) : null;

        $categoryList = $this->getDoctrine()->getManager()
            ->getRepository(Category::class)->findBy(['active' => true, 'category' => null], ['sort' => 'asc']);

        return $this->render('@App/Product/menu.html.twig', array(
            'categoryList' => $categoryList,
            'currentCategory' => $currentCategory,
        ));
    }

    /**
     * Category item
     *
     * @Route("/product/{categoryName}", name="categoryItem")
     *
     * @throws \Exception
     * @param Request $request
     * @param string $categoryName
     * @return Response
     */
    public function categoryAction(Request $request, string $categoryName)
    {
        $categoryItem = $this->getDoctrine()->getManager()
            ->getRepository(Category::class)->findOneBy(['active' => true, 'name' => $categoryName]);
        if (!$categoryItem) {
            throw new NotFoundHttpException('Страница не найдена');
        }

        $productList = $this->getDoctrine()->getManager()
            ->getRepository(Product::class)->findByCategory($categoryItem);
        $this->priceManager->update($productList);

        return $this->render('@App/Product/category.html.twig', array(
            'categoryItem' => $categoryItem,
            'productList' => $productList,
        ));
    }

    /**
     * Product item
     *
     * @Route("/product/{categoryName}/{id}", name="productItem")
     *
     * @throws \Exception
     * @param Request $request
     * @param string $categoryName
     * @param int $id
     * @return Response
     */
    public function productAction(Request $request, $categoryName, int $id)
    {
        $productItem = $this->getDoctrine()->getManager()
            ->getRepository(Product::class)->findOneBy(['id' => $id, 'active' => true]);
        if (!$productItem) {
            throw new NotFoundHttpException('Страница не найдена');
        }

        $this->priceManager->update($productItem);

        return $this->render('@App/Product/item.html.twig', array(
            'productItem' => $productItem,
        ));
    }

    /**
     * Search result
     *
     * @Route("/search", name="search")
     *
     * @throws \Exception
     * @param Request $request
     * @return Response
     */
    public function searchAction(Request $request)
    {
        $productList = array();

        $text = (string) $request->request->get('text');
        if ($text !== '') {
            $productList = $this->getDoctrine()->getManager()
                ->getRepository(Product::class)->findByText($text);
            $this->priceManager->update($productList);
        }

        return $this->render('@App/Product/search.html.twig', array(
            'productList' => $productList,
        ));
    }

    /**
     * Random products
     *
     * @param Request $request
     * @param string $categoryName
     * @return Response
     */
    public function randomAction(Request $request, string $categoryName)
    {
        $categoryItem = $this->getDoctrine()->getManager()
            ->getRepository(Category::class)->findOneBy(['active' => true, 'name' => $categoryName]);

        if (!$categoryItem) {
            throw new NotFoundHttpException('Страница не найдена');
        }

        $categoryList = $this->getDoctrine()->getManager()
            ->getRepository(Category::class)->findBy(['active' => true, 'category' => $categoryItem]);

        shuffle($categoryList); $categoryItem = array_pop($categoryList);

        $productList = $this->getDoctrine()->getManager()
            ->getRepository(Product::class)->findByCategory($categoryItem);

        return $this->render('@App/Product/random.html.twig', array(
            'categoryItem' => $categoryItem,
            'productList' => $productList,
        ));
    }
}