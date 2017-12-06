<?php

namespace AppBundle\Controller;

use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use AppBundle\Entity\Category;
use AppBundle\Entity\Product;
use AppBundle\Util\Pager;

class ProductController extends Controller
{
    /**
     * @var int
     */
    const PRODUCTS_PER_PAGE = 20;

    /**
     * Category menu
     *
     * @param Request $request
     * @return Response
     */
    public function menuAction(Request $request)
    {
        $categoryList = $this->getDoctrine()->getManager()
            ->getRepository(Category::class)->findBy(['active' => true, 'category' => null]);

        return $this->render('@App/Product/menu.html.twig', array(
            'categoryList' => $categoryList,
        ));
    }

    /**
     * Category item
     *
     * @Route("/product/{categoryName}", name="categoryItem")
     *
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

        $page = max(1, $request->query->getInt('page', 1));
        $offset = ($page - 1) * self::PRODUCTS_PER_PAGE;
        $productList = $this->getDoctrine()->getManager()
            ->getRepository(Product::class)->findByCategory($categoryItem, $offset, self::PRODUCTS_PER_PAGE);

        return $this->render('@App/Product/category.html.twig', array(
            'categoryItem' => $categoryItem,
            'productList' => $productList,
            'pager' => Pager::build(count($productList), self::PRODUCTS_PER_PAGE, $page),
        ));
    }

    /**
     * Product item
     *
     * @Route("/product/{categoryName}/{id}", name="productItem")
     *
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

        return $this->render('@App/Product/item.html.twig', array(
            'productItem' => $productItem,
        ));
    }

    /**
     * Search result
     *
     * @Route("/search", name="search")
     *
     * @param Request $request
     * @return Response
     */
    public function searchAction(Request $request)
    {
        $page = max(1, $request->query->getInt('page', 1));
        $offset = ($page - 1) * self::PRODUCTS_PER_PAGE;

        $productList = array();
        $text = $request->query->get('text');
        if ($text !== '') {
            $productList = $this->getDoctrine()->getManager()
                ->getRepository(Product::class)->findByText($text, $offset, self::PRODUCTS_PER_PAGE);
        }

        return $this->render('@App/Product/search.html.twig', array(
            'text' => $text,
            'productList' => $productList,
            'pager' => Pager::build(count($productList), self::PRODUCTS_PER_PAGE, $page),
        ));
    }

    /**
     * Best products
     *
     * @param Request $request
     * @param int $limit
     * @return Response
     */
    public function bestAction(Request $request, int $limit = 4)
    {
        $productList = $this->getDoctrine()->getManager()
            ->getRepository(Product::class)->findByBest();

        shuffle($productList);

        return $this->render('@App/Product/best.html.twig', array(
            'productList' => array_slice($productList, 0, $limit),
        ));
    }

    /**
     * Pricelist
     *
     * @Route("/price_list", name="price_list")
     *
     * @param Request $request
     * @return Response
     */
    public function priceAction(Request $request)
    {
        $categoryList = $this->getDoctrine()->getManager()
            ->getRepository(Category::class)->findBy(['active' => true, 'category' => null]);

        return $this->render('@App/Product/price.html.twig', array(
            'categoryList' => $categoryList
        ));
    }
}