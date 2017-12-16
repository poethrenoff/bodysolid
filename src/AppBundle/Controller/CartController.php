<?php

namespace AppBundle\Controller;

use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use AppBundle\Service\Cart;
use AppBundle\Service\PreferenceStorage;
use AppBundle\Entity\Product;

class CartController extends Controller
{
    /**
     * @var Cart
     */
    protected $cart;

    /**
     * @var PreferenceStorage
     */
    protected $preferenceStorage;

    /**
     * CartController constructor
     *
     * @param Cart $cart
     */
    public function __construct(Cart $cart,
                                PreferenceStorage $preferenceStorage)
    {
        $this->cart = $cart;
        $this->preferenceStorage = $preferenceStorage;
    }
    
    /**
     * Cart view
     *
     * @Route("/cart", name="cart")
     *
     * @param Request $request
     * @return Response
     */
    public function indexAction(Request $request)
    {
        $productList = array();
        foreach ($this->cart->getItems() as $item ) {
            $productList[$item->id] = $this->getProduct($item->id);
        }

        return $this->render('@App/Cart/cart.html.twig', [
            'productList' => $productList
        ]);
    }

    /**
     * Cart info
     *
     * @Route("/cart/info", name="cart_info")
     *
     * @param Request $request
     * @return Response
     */
    public function infoAction(Request $request)
    {
        return $this->render('@App/Cart/info.html.twig');
    }

    /**
     * Add product
     *
     * @Route("/cart/add/{id}", name="cart_add")
     *
     * @throws \Exception
     * @param Request $request
     * @return Response
     */
    public function addAction(Request $request, $id)
    {
        $productItem = $this->getProduct($id);

        $quantity = max(1, intval($request->query->get('quantity')));

        $rate = $this->preferenceStorage->get('rate');
        $this->cart->add($productItem->getId(), $productItem->getFinalPrice($rate), $quantity);

        return $this->infoAction($request);
    }

    /**
     * Inc product
     *
     * @Route("/cart/inc/{id}", name="cart_inc")
     *
     * @param Request $request
     * @param int $id
     * @return Response
     */
    public function incAction(Request $request, int $id)
    {
        $quantity = max(1, intval($request->query->get('quantity')));

        $this->cart->inc($id, $quantity);

        return $this->infoAction($request);
    }

    /**
     * Dec product
     *
     * @Route("/cart/dec/{id}", name="cart_dec")
     *
     * @param Request $request
     * @param int $id
     * @return Response
     */
    public function decAction(Request $request, int $id)
    {
        $quantity = max(1, intval($request->query->get('quantity')));

        $this->cart->dec($id, $quantity);

        return $this->infoAction($request);
    }

    /**
     * Delete product
     *
     * @Route("/cart/delete/{id}", name="cart_delete")
     *
     * @param Request $request
     * @param int $id
     * @return Response
     */
    public function deleteAction(Request $request, $id)
    {
        $this->cart->delete($id);

        return $this->redirectToRoute('cart');
    }

    /**
     * Clear cart
     *
     * @Route("/cart/clear", name="cart_clear")
     *
     * @param Request $request
     * @return Response
     */
    public function clearAction(Request $request)
    {
        $this->cart->clear();

        return $this->redirectToRoute('cart');
    }

    /**
     * Get product
     *
     * @param int $id
     * @return Product
     */
    protected function getProduct($id)
    {
        $productItem = $this->getDoctrine()->getManager()
            ->getRepository(Product::class)->findOneBy(['id' => $id, 'active' => true]);

        if (!$productItem) {
            throw new NotFoundHttpException('Страница не найдена');
        }

        return $productItem;
    }
}