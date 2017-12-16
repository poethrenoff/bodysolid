<?php

namespace AppBundle\Entity;

use Symfony\Component\Validator\Constraints as Assert;

/**
 * Discount
 */
class Discount
{
    /**
     * @var string
     *
     * @Assert\NotBlank(message="Поле обязательно для заполнения")
     */
    protected $person;

    /**
     * @var string
     *
     * @Assert\Email(message="Неверное значение email")
     * @Assert\NotBlank(message="Поле обязательно для заполнения")
     */
    protected $email;

    /**
     * @var string
     *
     * @Assert\NotBlank(message="Поле обязательно для заполнения")
     */
    protected $phone;

    /**
     * @var string
     *
     * @Assert\NotBlank(message="Поле обязательно для заполнения")
     */
    protected $product;

    /**
     * @var int
     *
     * @Assert\GreaterThan(value=0, message="Неверное значение количества")
     * @Assert\NotBlank(message="Поле обязательно для заполнения")
     */
    protected $quantity;

    /**
     * @return string
     */
    public function getPerson(): ?string
    {
        return $this->person;
    }

    /**
     * @param string $person
     * @return Discount
     */
    public function setPerson(?string $person): Discount
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
     * @return Discount
     */
    public function setEmail(?string $email): Discount
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
     * @return Discount
     */
    public function setPhone(?string $phone): Discount
    {
        $this->phone = $phone;
        return $this;
    }

    /**
     * @return string
     */
    public function getProduct(): ?string
    {
        return $this->product;
    }

    /**
     * @param string $product
     * @return Discount
     */
    public function setProduct(?string $product): Discount
    {
        $this->product = $product;
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
     * @return Discount
     */
    public function setQuantity(?int $quantity): Discount
    {
        $this->quantity = $quantity;
        return $this;
    }
}