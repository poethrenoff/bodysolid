<?php
namespace AppBundle\Entity;

use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Validator\Context\ExecutionContext;

/**
 * Subscribe
 */
class Subscribe
{
    /**
     * @var string
     *
     * @Assert\Email(message="Неверное значение email")
     * @Assert\NotBlank(message="Поле обязательно к заполнению")
     */
    protected $email;

    /**
     * @var string
     *
     * @Assert\NotBlank(message="Поле обязательно к заполнению")
     */
    protected $person;

    /**
     * @var string
     */
    protected $company;

    /**
     * @var string
     * 
     * @Assert\Choice({"wholesale", "retail"}, message="Неверное значение типа компании")
     */
    protected $type;

    /**
     * @var string
     */
    protected $phone;

    /**
     * @var string
     */
    protected $url;

    /**
     * @return string
     */
    public function getEmail(): ?string
    {
        return $this->email;
    }

    /**
     * @param string $email
     * @return Subscribe
     */
    public function setEmail(?string $email): Subscribe
    {
        $this->email = $email;
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
     * @return Subscribe
     */
    public function setPerson(?string $person): Subscribe
    {
        $this->person = $person;
        return $this;
    }

    /**
     * @return string
     */
    public function getCompany(): ?string
    {
        return $this->company;
    }

    /**
     * @param string $company
     * @return Subscribe
     */
    public function setCompany(?string $company): Subscribe
    {
        $this->company = $company;
        return $this;
    }

    /**
     * @return string
     */
    public function getType(): ?string
    {
        return $this->type;
    }

    /**
     * @param string $type
     * @return Subscribe
     */
    public function setType(?string $type): Subscribe
    {
        $this->type = $type;
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
     * @return Subscribe
     */
    public function setPhone(?string $phone): Subscribe
    {
        $this->phone = $phone;
        return $this;
    }

    /**
     * @return string
     */
    public function getUrl(): ?string
    {
        return $this->url;
    }

    /**
     * @param string $url
     * @return Subscribe
     */
    public function setUrl(?string $url): Subscribe
    {
        $this->url = $url;
        return $this;
    }

    /**
     * @Assert\Callback
     */
    public function validate(ExecutionContext $context, $payload)
    {
        if ($this->getCompany()) {
            if (empty($this->getType())) {
                $context->buildViolation('Поле обязательно к заполнению')
                    ->atPath('type')
                    ->addViolation();
            }
            if (empty($this->getPhone())) {
                $context->buildViolation('Поле обязательно к заполнению')
                    ->atPath('phone')
                    ->addViolation();
            }
        }
    }    
}
