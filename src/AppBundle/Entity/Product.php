<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Product
 *
 * @ORM\Table(name="product")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\ProductRepository")
 */
class Product
{
    /**
     * @var string
     */
    const DEFAULT_PICTURE = '/image/default.jpg';

    /**
     * @var int
     *
     * @ORM\Column(type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @var int
     *
     * @ORM\Column(type="integer", nullable=true)
     */
    protected $externalId;

    /**
     * @var Category
     *
     * @ORM\ManyToOne(targetEntity="Category", inversedBy="products")
     * @ORM\JoinColumn(referencedColumnName="id")
     */
    protected $category;

    /**
     * @var Brand
     *
     * @Assert\NotBlank()
     * @ORM\ManyToOne(targetEntity="Brand")
     * @ORM\JoinColumn(referencedColumnName="id")
     */
    protected $brand;

    /**
     * @var string
     *
     * @Assert\NotBlank()
     * @ORM\Column(type="string")
     */
    protected $title;

    /**
     * @var float
     *
     * @ORM\Column(type="decimal", scale=2, nullable=true)
     */
    protected $price;

    /**
     * @var float
     *
     * @ORM\Column(type="decimal", scale=2, nullable=true)
     */
    protected $priceUsd;

    /**
     * @var string
     *
     * @ORM\Column(type="text", nullable=true)
     */
    protected $description;

    /**
     * @var float
     *
     * @ORM\Column(type="decimal", scale=2, nullable=true)
     */
    protected $discount;

    /**
     * @var boolean
     *
     * @ORM\Column(type="boolean")
     */
    protected $noDiscount = false;

    /**
     * @var boolean
     *
     * @ORM\Column(type="boolean")
     */
    protected $active = true;

    /**
     * @ORM\OneToMany(targetEntity="ProductPicture", mappedBy="product")
     * @ORM\OrderBy({"sort" = "asc"})
     */
    protected $pictures;

    /**
     * @ORM\OneToMany(targetEntity="ProductFile", mappedBy="product")
     * @ORM\OrderBy({"sort" = "asc"})
     */
    protected $files;

    /**
     * @ORM\OneToMany(targetEntity="ProductVideo", mappedBy="product")
     * @ORM\OrderBy({"sort" = "asc"})
     */
    protected $videos;

    /**
     * Product constructor
     */
    public function __construct()
    {
        $this->pictures = new ArrayCollection();
        $this->files = new ArrayCollection();
        $this->videos = new ArrayCollection();
    }

    /**
     * @return int
     */
    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @param int $id
     * @return Product
     */
    public function setId(?int $id): Product
    {
        $this->id = $id;
        return $this;
    }

    /**
     * @return int
     */
    public function getExternalId(): ?int
    {
        return $this->externalId;
    }

    /**
     * @param int $externalId
     * @return Product
     */
    public function setExternalId(?int $externalId): Product
    {
        $this->externalId = $externalId;
        return $this;
    }

    /**
     * @return Category
     */
    public function getCategory(): ?Category
    {
        return $this->category;
    }

    /**
     * @param Category $category
     * @return Product
     */
    public function setCategory(?Category $category): Product
    {
        $this->category = $category;
        return $this;
    }

    /**
     * @return Brand
     */
    public function getBrand(): ?Brand
    {
        return $this->brand;
    }

    /**
     * @param Brand $brand
     * @return Product
     */
    public function setBrand(?Brand $brand): Product
    {
        $this->brand = $brand;
        return $this;
    }

    /**
     * @return string
     */
    public function getTitle(): ?string
    {
        return $this->title;
    }

    /**
     * @param string $title
     * @return Product
     */
    public function setTitle(?string $title): Product
    {
        $this->title = $title;
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
     * @return Product
     */
    public function setPrice(?float $price): Product
    {
        $this->price = $price;
        return $this;
    }

    /**
     * @return float
     */
    public function getPriceUsd(): ?float
    {
        return $this->priceUsd;
    }

    /**
     * @param float $priceUsd
     * @return Product
     */
    public function setPriceUsd(?float $priceUsd): Product
    {
        $this->priceUsd = $priceUsd;
        return $this;
    }

    /**
     * @param float $rate
     * @return float
     */
    public function getPriceRub(float $rate = 1): ?float
    {
        return $this->getPriceUsd() ? ($this->getPriceUsd() * $rate) : $this->getPrice();
    }

    /**
     * @param float $rate
     * @return float
     */
    public function getRawPrice(float $rate = 1): ?float
    {
        $priceRub = $this->getPriceRub($rate);

        return ceil($priceRub / 10) * 10;
    }

    /**
     * @param float $rate
     * @return float
     */
    public function getFinalPrice(float $rate = 1): ?float
    {
        $priceRub = $this->getPriceRub($rate);

        $discount = !$this->isNoDiscount() ? (
            $this->getDiscount() ?: (
                $this->getCategory()->getDiscount() ?: 0
            )
        ) : 0;

        $price = $discount ? (
            $priceRub * (100 - $discount) / 100
        ) : $priceRub;

        return ceil($price / 10) * 10;
    }

    /**
     * @return string
     */
    public function getDescription(): ?string
    {
        return $this->description;
    }

    /**
     * @param string $description
     * @return Product
     */
    public function setDescription(?string $description): Product
    {
        $this->description = $description;
        return $this;
    }

    /**
     * @return bool
     */
    public function isActive(): ?bool
    {
        return $this->active;
    }

    /**
     * @param bool $active
     * @return Product
     */
    public function setActive(?bool $active): Product
    {
        $this->active = $active;
        return $this;
    }

    /**
     * @return float
     */
    public function getDiscount(): ?float
    {
        return $this->discount;
    }

    /**
     * @param float $discount
     * @return Product
     */
    public function setDiscount(?float $discount): Product
    {
        $this->discount = $discount;
        return $this;
    }

    /**
     * @return bool
     */
    public function isNoDiscount(): ?bool
    {
        return $this->noDiscount;
    }

    /**
     * @param bool $noDiscount
     * @return Product
     */
    public function setNoDiscount(?bool $noDiscount): Product
    {
        $this->noDiscount = $noDiscount;
        return $this;
    }

    /**
     * @return ProductPicture
     */
    public function getImage()
    {
        if (count($this->pictures)) {
            return $this->pictures->first();
        }

        $picture = new ProductPicture();
        $picture->setImage(self::DEFAULT_PICTURE);

        return $picture;
    }

    /**
     * @return Collection
     */
    public function getPictures(): Collection
    {
        return $this->pictures;
    }

    /**
     * @return Collection
     */
    public function getFiles(): Collection
    {
        return $this->files;
    }

    /**
     * @return Collection
     */
    public function getVideos(): Collection
    {
        return $this->videos;
    }

    /**
     * @return string
     */
    public function __toString(): string
    {
        return (string)$this->getTitle();
    }
}