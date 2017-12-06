<?php

namespace AppBundle\Entity;

use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Validator\Context\ExecutionContext;

/**
 * Callback
 */
class Callback
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
     * @Assert\NotBlank(message="Поле обязательно для заполнения")
     */
    protected $phone;

    /**
     * @var string
     */
    protected $comment;

    /**
     * @return string
     */
    public function getPerson(): ?string
    {
        return $this->person;
    }

    /**
     * @param string $person
     * @return Callback
     */
    public function setPerson(?string $person): Callback
    {
        $this->person = $person;
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
     * @return Callback
     */
    public function setPhone(?string $phone): Callback
    {
        $this->phone = $phone;
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
     * @return Callback
     */
    public function setComment(?string $comment): Callback
    {
        $this->comment = $comment;
        return $this;
    }
}