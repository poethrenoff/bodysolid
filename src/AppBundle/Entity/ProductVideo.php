<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * ProductVideo
 *
 * @ORM\Entity
 * @ORM\Table(name="product_video")
 */
class ProductVideo
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
     * @ORM\ManyToOne(targetEntity="Product", inversedBy="videos")
     * @ORM\JoinColumn(referencedColumnName="id")
     */
    protected $product;

    /**
     * @var string
     *
     * @Assert\NotBlank()
     * @ORM\Column(type="text")
     */
    protected $video;

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
     * @return ProductVideo
     */
    public function setId(?int $id): ProductVideo
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
     * @return ProductVideo
     */
    public function setProduct(?Product $product): ProductVideo
    {
        $this->product = $product;
        return $this;
    }

    /**
     * @return string
     */
    public function getVideo(): ?string
    {
        return $this->video;
    }

    /**
     * @param string $video
     * @return ProductVideo
     */
    public function setVideo(?string $video): ProductVideo
    {
        $this->video = $video;
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
     * @return ProductVideo
     */
    public function setSort(?int $sort): ProductVideo
    {
        $this->sort = $sort;
        return $this;
    }

    /**
     * @return string
     */
    public function __toString(): string
    {
        return (string)$this->getVideo();
    }
}