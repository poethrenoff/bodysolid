<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\Validator\Context\ExecutionContext;

/**
 * ProductPicture
 *
 * @ORM\Entity
 * @ORM\Table(name="product_picture")
 */
class ProductPicture
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
     * @var Product
     *
     * @Assert\NotBlank()
     * @ORM\ManyToOne(targetEntity="Product", inversedBy="pictures")
     * @ORM\JoinColumn(referencedColumnName="id", onDelete="CASCADE")
     */
    protected $product;

    /**
     * @var string
     *
     * @ORM\Column(type="string")
     */
    protected $image;

    /**
     * @var File
     */
    protected $imageFile;

    /**
     * @var int
     *
     * @Assert\NotBlank()
     * @ORM\Column(type="integer")
     */
    protected $sort;

    /**
     * @return int
     */
    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @param int $id
     * @return ProductPicture
     */
    public function setId(?int $id): ProductPicture
    {
        $this->id = $id;
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
     * @return ProductPicture
     */
    public function setProduct(?Product $product): ProductPicture
    {
        $this->product = $product;
        return $this;
    }

    /**
     * @return string
     */
    public function getImage(): ?string
    {
        return $this->image;
    }

    /**
     * @param string $image
     * @return ProductPicture
     */
    public function setImage(?string $image): ProductPicture
    {
        $this->image = $image;
        return $this;
    }

    /**
     * @return File
     */
    public function getImageFile(): ?File
    {
        return $this->imageFile;
    }

    /**
     * @param File $imageFile
     * @return ProductPicture
     */
    public function setImageFile(?File $imageFile): ProductPicture
    {
        $this->imageFile = $imageFile;
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
     * @return ProductPicture
     */
    public function setSort(?int $sort): ProductPicture
    {
        $this->sort = $sort;
        return $this;
    }

    /**
     * @return string
     */
    public function __toString(): string
    {
        return (string)$this->getImage();
    }

    /**
     * @Assert\Callback
     */
    public function validate(ExecutionContext $context)
    {
        if (empty($this->getImage()) && empty($this->getImageFile())) {
            $context->buildViolation('Одно из полей обязательно к заполнению')
                ->atPath('image')
                ->addViolation();
            $context->buildViolation('Одно из полей обязательно к заполнению')
                ->atPath('imageFile')
                ->addViolation();
        }
    }
}