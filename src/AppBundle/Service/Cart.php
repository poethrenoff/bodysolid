<?php

namespace AppBundle\Service;

use Symfony\Component\HttpFoundation\Session\SessionInterface;

/**
 * Class Cart
 */
class Cart
{
    /**
     * @var string
     */
    const SESSION_VAR = '__CART__';

    /**
     * @var SessionInterface
     */
    protected $session;

    /**
     * @var array
     */
    protected $items = array();

    /**
     * Cart constructor
     *
     * @param SessionInterface $session
     */
    public function __construct(SessionInterface $session)
    {
        $this->session = $session;

        if (!$this->session->has(self::SESSION_VAR) || !is_array($this->session->get(self::SESSION_VAR))) {
            $this->session->set(self::SESSION_VAR, array());
        }

        $this->items = $this->session->get(self::SESSION_VAR);
    }

    public function save()
    {
        $this->session->set(self::SESSION_VAR, $this->items);
    }

    public function add($id, $price, $quantity = 1)
    {
        if (isset($this->items[$id])) {
            $this->items[$id]->quantity += $quantity;
        } else {
            $item = new \StdClass();

            $item->id = $id;
            $item->price = $price;
            $item->quantity = $quantity;

            $this->items[$id] = $item;
        }
        $this->save();
    }

    public function inc($id, $quantity = 1)
    {
        if (isset($this->items[$id])) {
            $this->items[$id]->quantity += $quantity;
        }
        $this->save();
    }

    public function dec($id, $quantity = 1)
    {
        if (isset($this->items[$id]) && ($this->items[$id]->quantity > $quantity)) {
            $this->items[$id]->quantity -= $quantity;
        }
        $this->save();
    }

    public function in($id)
    {
        return isset($this->items[$id]);
    }

    public function delete($id)
    {
        unset($this->items[$id]);
        $this->save();
    }

    public function clear()
    {
        $this->items = array();
        $this->save();
    }

    public function getItems()
    {
        return $this->items;
    }

    public function getQuantity()
    {
        $cart_count = 0;
        foreach ($this->items as $item) {
            $cart_count += $item->quantity;
        }
        return $cart_count;
    }

    public function getSum()
    {
        $cart_sum = 0;
        foreach ($this->items as $item) {
            $cart_sum += $item->quantity * $item->price;
        }
        return $cart_sum;
    }
}