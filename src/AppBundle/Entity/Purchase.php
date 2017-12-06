<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Purchase
 *
 * @ORM\Entity
 * @ORM\Table(name="purchase")
 */
class Purchase
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
     * @var string
     *
     * @Assert\NotBlank(message="Поле обязательно для заполнения")
     * @ORM\Column(type="string")
     */
    protected $person;

    /**
     * @var string
     *
     * @Assert\Email(message="Неверное значение email")
     * @Assert\NotBlank(message="Поле обязательно для заполнения")
     * @ORM\Column(type="string")
     */
    protected $email;

    /**
     * @var string
     *
     * @Assert\NotBlank(message="Поле обязательно для заполнения")
     * @ORM\Column(type="string")
     */
    protected $phone;

    /**
     * @var string
     *
     * @Assert\NotBlank(message="Поле обязательно для заполнения")
     * @ORM\Column(type="text")
     */
    protected $address;

    /**
     * @var string
     *
     * @ORM\Column(type="text", nullable=true)
     */
    protected $comment;

    /**
     * @var \DateTime
     *
     * @Assert\NotBlank()
     * @ORM\Column(type="datetime")
     */
    protected $date;

    /**
     * @var float
     *
     * @Assert\NotBlank()
     * @ORM\Column(type="decimal", scale=2)
     */
    protected $sum = 0;

    /**
     * @var string
     *
     * @Assert\NotBlank()
     * @Assert\Choice({"new", "confirm", "deliver", "complete", "cancel"})
     * @ORM\Column(type="string")
     */
    protected $status = 'new';

    /**
     * @ORM\OneToMany(targetEntity="PurchaseItem", mappedBy="item_purchase", cascade={"all"})
     * @ORM\OrderBy({"id" = "asc"})
     */
    protected $items;

    /**
     * Purchase constructor
     */
    public function __construct()
    {
        $this->date = new \DateTime();
        $this->items = new ArrayCollection();
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
     * @return Purchase
     */
    public function setId(?int $id): Purchase
    {
        $this->id = $id;
        return $this;
    }

    /**
     * @return string
     */
    public function getPerson(): ?string
    {
        return $this->person;
    }

    /**
     * @param string $person
     * @return Purchase
     */
    public function setPerson(?string $person): Purchase
    {
        $this->person = $person;
        return $this;
    }

    /**
     * @return string
     */
    public function getEmail(): ?string
    {
        return $this->email;
    }

    /**
     * @param string $email
     * @return Purchase
     */
    public function setEmail(?string $email): Purchase
    {
        $this->email = $email;
        return $this;
    }

    /**
     * @return string
     */
    public function getPhone(): ?string
    {
        return $this->phone;
    }

    /**
     * @param string $phone
     * @return Purchase
     */
    public function setPhone(?string $phone): Purchase
    {
        $this->phone = $phone;
        return $this;
    }

    /**
     * @return string
     */
    public function getAddress(): ?string
    {
        return $this->address;
    }

    /**
     * @param string $address
     * @return Purchase
     */
    public function setAddress(?string $address): Purchase
    {
        $this->address = $address;
        return $this;
    }

    /**
     * @return string
     */
    public function getComment(): ?string
    {
        return $this->comment;
    }

    /**
     * @param string $comment
     * @return Purchase
     */
    public function setComment(?string $comment): Purchase
    {
        $this->comment = $comment;
        return $this;
    }

    /**
     * @return \DateTime
     */
    public function getDate(): ?\DateTime
    {
        return $this->date;
    }

    /**
     * @param \DateTime $date
     * @return Purchase
     */
    public function setDate(?\DateTime $date): Purchase
    {
        $this->date = $date;
        return $this;
    }

    /**
     * @return float
     */
    public function getSum(): ?float
    {
        return $this->sum;
    }

    /**
     * @param float $sum
     * @return Purchase
     */
    public function setSum(?float $sum): Purchase
    {
        $this->sum = $sum;
        return $this;
    }

    /**
     * @return string
     */
    public function getStatus(): ?string
    {
        return $this->status;
    }

    /**
     * @param string $status
     * @return Purchase
     */
    public function setStatus(?string $status): Purchase
    {
        $this->status = $status;
        return $this;
    }

    /**
     * @param PurchaseItem $item
     * @return Purchase
     */
    public function addItem(PurchaseItem $item): Purchase
    {
        $this->items[] = $item;
        return $this;
    }

    /**
     * @param PurchaseItem $item
     * @return Purchase
     */
    public function removeItem(PurchaseItem $item): Purchase
    {
        $this->items->removeElement($item);
        return $this;
    }

    /**
     * @return Collection
     */
    public function getItems(): Collection
    {
        return $this->items;
    }

    /**
     * @return string
     */
    public function __toString(): string
    {
        return 'Заказ №' . $this->getId();
    }
}