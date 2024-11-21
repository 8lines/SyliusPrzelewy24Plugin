<?php

declare(strict_types=1);

namespace BitBag\SyliusPrzelewy24Plugin\Subscription\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;

class Subscriber implements SubscriberInterface
{
    private int $id;

    /**
     * @var Collection<CardInterface>
     */
    private Collection $cards;

    private ?SyliusCustomerAsSubscriberInterface $syliusCustomer;


    public function __construct()
    {
        $this->cards = new ArrayCollection();
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getCards(): Collection
    {
        return $this->cards;
    }

    public function addCard(CardInterface $card): void
    {
        $card->setOwner($this);
        $this->cards->add($card);
    }

    public function getSyliusCustomer(): ?SyliusCustomerAsSubscriberInterface
    {
        return $this->syliusCustomer;
    }

    public function setSyliusCustomer(?SyliusCustomerAsSubscriberInterface $syliusCustomer): void
    {
        $this->syliusCustomer = $syliusCustomer;
    }
}
