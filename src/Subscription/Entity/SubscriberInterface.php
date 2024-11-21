<?php

declare(strict_types=1);

namespace BitBag\SyliusPrzelewy24Plugin\Subscription\Entity;

use Doctrine\Common\Collections\Collection;
use Sylius\Resource\Model\ResourceInterface;

interface SubscriberInterface extends ResourceInterface
{
    /**
     * @return Collection<CardInterface>
     */
    public function getCards(): Collection;

    public function addCard(CardInterface $card);

    public function getSyliusCustomer(): ?SyliusCustomerAsSubscriberInterface;

    public function setSyliusCustomer(?SyliusCustomerAsSubscriberInterface $syliusCustomer): void;
}
