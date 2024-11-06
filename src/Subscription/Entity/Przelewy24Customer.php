<?php

declare(strict_types=1);

namespace BitBag\SyliusPrzelewy24Plugin\Subscription\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;

class Przelewy24Customer implements Przelewy24CustomerInterface
{
    private int $id;

    /**
     * @var Collection<Przelewy24CreditCardInterface>
     */
    private Collection $creditCards;

    private ?CustomerInterface $syliusCustomer;


    public function __construct()
    {
        $this->creditCards = new ArrayCollection();
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getCreditCards(): Collection
    {
        return $this->creditCards;
    }

    public function addCreditCard(Przelewy24CreditCard $creditCard): void
    {
        $creditCard->setOwner($this);
        $this->creditCards->add($creditCard);
    }

    public function getSyliusCustomer(): ?CustomerInterface
    {
        return $this->syliusCustomer;
    }

    public function setSyliusCustomer(?CustomerInterface $syliusCustomer): void
    {
        $this->syliusCustomer = $syliusCustomer;
    }
}
