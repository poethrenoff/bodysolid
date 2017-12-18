<?php

namespace AppBundle\Service;

use AppBundle\Entity\Product;
use AppBundle\Service\PreferenceStorage;

class PriceManager
{
    /**
     * @var PreferenceStorage
     */
    protected $preferenceStorage;

    /**
     * PriceManager constructor
     *
     * @param PreferenceStorage $preferenceStorage
     */
    public function __construct(PreferenceStorage $preferenceStorage)
    {
        $this->preferenceStorage = $preferenceStorage;
    }

    /**
     * Update product price
     *
     * @throws \Exception
     * @param Product|array $product
     */
    public function update($product)
    {
        if (is_array($product)) {
            array_walk($product, function($productItem) {
                $this->update($productItem);
            });
            return;
        }

        if (!$product instanceof Product) {
            throw new \Exception('Argument must be an instance of Product');
        }

        $rate = $this->preferenceStorage->get('rate');

        $rawPrice = $product->getPriceUsd() ? ($product->getPriceUsd() * $rate) : $product->getPrice();

        $discount = !$product->isNoDiscount() ? (
            $product->getDiscount() ?: (
                $product->getCategory()->getDiscount() ?: 0
            )
        ) : 0;

        $finalPrice = $discount ? ($rawPrice * (100 - $discount) / 100) : $rawPrice;

        $product->setRawPrice(ceil($rawPrice / 10) * 10);
        $product->setFinalPrice(ceil($finalPrice / 10) * 10);
    }
}