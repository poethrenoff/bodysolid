<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Event\LifecycleEventArgs;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Validator\Constraints as Assert;
use Cocur\Slugify\Slugify;

/**
 * Category
 *
 * @ORM\Entity
 * @ORM\Table(name="category")
 * @ORM\HasLifecycleCallbacks
 */
class Category
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
     * @var int
     *
     * @ORM\Column(type="integer", nullable=true)
     */
    protected $externalId;

    /**
     * @var Category
     *
     * @ORM\ManyToOne(targetEntity="Category", inversedBy="categories")
     * @ORM\JoinColumn(referencedColumnName="id")
     */
    protected $category;

    /**
     * @var string
     *
     * @Assert\NotBlank()
     * @ORM\Column(type="string")
     */
    protected $title;

    /**
     * @var string
     *
     * @Assert\NotBlank()
     * @ORM\Column(type="string")
     */
    protected $shortTitle;

    /**
     * @var string
     *
     * @Assert\Regex(pattern="/^[a-z0-9-_]+$/i")
     * @ORM\Column(type="string")
     */
    protected $name;

    /**
     * @var float
     *
     * @ORM\Column(type="decimal", scale=2)
     */
    protected $discount;

    /**
     * @var int
     *
     * @Assert\NotBlank()
     * @ORM\Column(type="integer")
     */
    protected $sort;

    /**
     * @var boolean
     *
     * @ORM\Column(type="boolean")
     */
    protected $active = true;

    /**
     * @ORM\OneToMany(targetEntity="Category", mappedBy="category")
     * @ORM\OrderBy({"sort" = "asc"})
     */
    protected $categories;

    /**
     * @ORM\OneToMany(targetEntity="Product", mappedBy="category")
     * @ORM\OrderBy({"price" = "asc"})
     */
    protected $products;

    /**
     * Category constructor
     */
    public function __construct()
    {
        $this->categories = new ArrayCollection();
        $this->products = new ArrayCollection();
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
     * @return Category
     */
    public function setId(?int $id): Category
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
     * @return Category
     */
    public function setExternalId(?int $externalId): Category
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
     * @return Category
     */
    public function setCategory(?Category $category): Category
    {
        $this->category = $category;
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
     * @return Category
     */
    public function setTitle(?string $title): Category
    {
        $this->title = $title;
        return $this;
    }

    /**
     * @return string
     */
    public function getName(): ?string
    {
        return $this->name;
    }

    /**
     * @param string $name
     * @return Category
     */
    public function setName(?string $name): Category
    {
        $this->name = $name;
        return $this;
    }

    /**
     * @return int
     */
    public function getSort(): ?int
    {
        return $this->sort;
    }

    /**
     * @param int $sort
     * @return Category
     */
    public function setSort(?int $sort): Category
    {
        $this->sort = $sort;
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
     * @return Category
     */
    public function setActive(?bool $active): Category
    {
        $this->active = $active;
        return $this;
    }

    /**
     * @return Collection
     */
    public function getCategories(): Collection
    {
        return $this->categories->filter(function (Category $category) {
            return $category->isActive();
        });
    }

    /**
     * @return Collection
     */
    public function getProducts(): Collection
    {
        return $this->products->filter(function (Product $product) {
            return $product->isActive();
        });
    }

    /**
     * @return string
     */
    public function __toString(): string
    {
        return (string)$this->getTitle();
    }

    /**
     * @return string
     */
    public function getShortTitle(): ?string
    {
        return $this->shortTitle;
    }

    /**
     * @param string $shortTitle
     * @return Category
     */
    public function setShortTitle(?string $shortTitle): Category
    {
        $this->shortTitle = $shortTitle;
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
     * @return Category
     */
    public function setDiscount(?float $discount): Category
    {
        $this->discount = $discount;
        return $this;
    }

    /**
     * @ORM\PrePersist
     * @ORM\PreUpdate
     *
     * @param LifecycleEventArgs $args
     * @return void
     */
    public function slugify(LifecycleEventArgs $args): void
    {
        if (empty($this->getName())) {
            $this->setName((new Slugify())->slugify($this->getShortTitle()));
        }
    }
}