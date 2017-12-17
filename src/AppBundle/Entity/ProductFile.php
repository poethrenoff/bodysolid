<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\Validator\Context\ExecutionContext;

/**
 * ProductFile
 *
 * @ORM\Entity
 * @ORM\Table(name="product_file")
 */
class ProductFile
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
     * @Assert\NotBlank()
     * @ORM\Column(type="string")
     */
    protected $title;

    /**
     * @var string
     *
     * @ORM\Column(type="string")
     */
    protected $file;

    /**
     * @var File
     */
    protected $fileFile;

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
     * @return ProductFile
     */
    public function setId(?int $id): ProductFile
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
     * @return ProductFile
     */
    public function setProduct(?Product $product): ProductFile
    {
        $this->product = $product;
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
     * @return ProductFile
     */
    public function setTitle(?string $title): ProductFile
    {
        $this->title = $title;
        return $this;
    }

    /**
     * @return string
     */
    public function getFile(): ?string
    {
        return $this->file;
    }

    /**
     * @param string $file
     * @return ProductFile
     */
    public function setFile(?string $file): ProductFile
    {
        $this->file = $file;
        return $this;
    }

    /**
     * @return File
     */
    public function getFileFile(): ?File
    {
        return $this->fileFile;
    }

    /**
     * @param File $fileFile
     * @return ProductFile
     */
    public function setFileFile(?File $fileFile): ProductFile
    {
        $this->fileFile = $fileFile;
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
     * @return ProductFile
     */
    public function setSort(?int $sort): ProductFile
    {
        $this->sort = $sort;
        return $this;
    }

    /**
     * @return string
     */
    public function __toString(): string
    {
        return (string)$this->getFile();
    }

    /**
     * @Assert\Callback
     */
    public function validate(ExecutionContext $context)
    {
        if (empty($this->getFile()) && empty($this->getFileFile())) {
            $context->buildViolation('Одно из полей обязательно к заполнению')
                ->atPath('file')
                ->addViolation();
            $context->buildViolation('Одно из полей обязательно к заполнению')
                ->atPath('fileFile')
                ->addViolation();
        }
    }
}