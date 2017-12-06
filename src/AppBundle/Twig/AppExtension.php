<?php

namespace AppBundle\Twig;

use AppBundle\Service\Cart;
use AppBundle\Service\PreferenceStorage;
use Symfony\Component\HttpFoundation\RequestStack;

/**
 * AppExtension
 */
class AppExtension extends \Twig_Extension
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
     * @var RequestStack
     */
    private $requestStack;

    /**
     * @param Cart $cart
     * @param PreferenceStorage $preferenceStorage
     * @param RequestStack $requestStack
     */
    public function __construct(Cart $cart, PreferenceStorage $preferenceStorage, RequestStack $requestStack)
    {
        $this->cart = $cart;
        $this->preferenceStorage = $preferenceStorage;
        $this->requestStack = $requestStack;
    }

    /**
     * {@inheritdoc}
     */
    public function getFunctions()
    {
        return [
            new \Twig_Function('cart', array($this, 'cart')),
            new \Twig_Function('preference', array($this, 'preference')),
            new \Twig_Function('page', array($this, 'page')),
        ];
    }

    /**
     * @return Cart
     */
    public function cart()
    {
        return $this->cart;
    }

    /**
     * @throws \Exception
     *
     * @param  string $name
     * @param  string|null $default
     * @return string
     */
    public function preference(string $name, string $default = null)
    {
        return $this->preferenceStorage->get($name, $default);
    }

    /**
     * @param int $page
     * @param string $name
     *
     * @return string
     */
    public function page($page, $name = 'page')
    {
        $request = $this->requestStack->getCurrentRequest();
        $baseUrl = $request->getBaseUrl() . $request->getPathInfo();
        $pageQuery = http_build_query(array_merge($request->query->all(), [$name => $page]));
        return $baseUrl . '?' . $pageQuery;
    }
}
