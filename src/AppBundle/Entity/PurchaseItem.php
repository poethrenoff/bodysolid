<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * PurchaseItem
 *
 * @ORM\Entity
 * @ORM\Table(name="purchase_item")
 */
class PurchaseItem
{
    /**
     * @var int
     *
     * @ORM\Column(type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @var Purchase
     *
     * @Assert\NotBlank()
     * @ORM\ManyToOne(targetEntity="Purchase", inversedBy="items")
     * @ORM\JoinColumn(referencedColumnName="id")
     */
    protected $purchase;

    /**
     * @var Product
     *
     * @Assert\NotBlank()
     * @ORM\ManyToOne(targetEntity="Product")
     * @ORM\JoinColumn(referencedColumnName="id")
     */
    protected $product;

    /**
     * @var float
     *
     * @Assert\NotBlank()
     * @ORM\Column(type="decimal", scale=2)
     */
    protected $price;

    /**
     * @var int
     *
     * @Assert\NotBlank()
     * @ORM\Column(type="integer")
     */
    protected $quantity;

    /**
     * @return int
     */
    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @param int $id
     * @return PurchaseItem
     */
    public function setId(?int $id): PurchaseItem
    {
        $this->id = $id;
        return $this;
    }

    /**
     * @return Purchase
     */
    public function getPurchase(): ?Purchase
    {
        return $this->purchase;
    }

    /**
     * @param Purchase $purchase
     * @return PurchaseItem
     */
    public function setPurchase(?Purchase $purchase): PurchaseItem
    {
        $this->purchase = $purchase;
        return $this;
    }

    /**
     * @return Product
     */
    public function getProduct(): ?Product
    {
        return $this->product;
    }

    /**
     * @param Product $product
     * @return PurchaseItem
     */
    public function setProduct(?Product $product): PurchaseItem
    {
        $this->product = $product;
        return $this;
    }

    /**
     * @return float
     */
    public function getPrice(): ?float
    {
        return $this->price;
    }

    /**
     * @param float $price
     * @return PurchaseItem
     */
    public function setPrice(?float $price): PurchaseItem
    {
        $this->price = $price;
        return $this;
    }

    /**
     * @return int
     */
    public function getQuantity(): ?int
    {
        return $this->quantity;
    }

    /**
     * @param int $quantity
     * @return PurchaseItem
     */
    public function setQuantity(?int $quantity): PurchaseItem
    {
        $this->quantity = $quantity;
        return $this;
    }
}