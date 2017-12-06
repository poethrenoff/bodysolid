<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Event\LifecycleEventArgs;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Validator\Constraints as Assert;
use Cocur\Slugify\Slugify;

/**
 * Text
 *
 * @ORM\Entity
 * @ORM\Table(name="text")
 * @ORM\HasLifecycleCallbacks
 */
class Text
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
     * @var Site
     *
     * @ORM\ManyToOne(targetEntity="Site")
     * @ORM\JoinColumn(referencedColumnName="id")
     */
    protected $site;

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
     * @Assert\Regex(pattern="/^[a-z0-9-_]+$/i")
     * @ORM\Column(type="string")
     */
    protected $name;

    /**
     * @var string
     *
     * @Assert\NotBlank()
     * @ORM\Column(type="text")
     */
    protected $text;

    /**
     * @return int
     */
    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @param int $id
     * @return Text
     */
    public function setId(?int $id): Text
    {
        $this->id = $id;
        return $this;
    }

    /**
     * @return Site
     */
    public function getSite(): ?Site
    {
        return $this->site;
    }

    /**
     * @param Site $site
     * @return Text
     */
    public function setSite(?Site $site): Text
    {
        $this->site = $site;
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
     * @return Text
     */
    public function setTitle(?string $title): Text
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
     * @return Text
     */
    public function setName(?string $name): Text
    {
        $this->name = $name;
        return $this;
    }

    /**
     * @return string
     */
    public function getText(): ?string
    {
        return $this->text;
    }

    /**
     * @param string $text
     * @return Text
     */
    public function setText(?string $text): Text
    {
        $this->text = $text;
        return $this;
    }

    /**
     * @return string
     */
    public function __toString(): string
    {
        return (string) $this->getTitle();
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
            $this->setName((new Slugify())->slugify($this->getTitle()));
        }
    }
}