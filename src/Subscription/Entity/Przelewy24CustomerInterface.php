<?php

declare(strict_types=1);

namespace BitBag\SyliusPrzelewy24Plugin\Subscription\Entity;

use Doctrine\Common\Collections\Collection;
use Sylius\Resource\Model\ResourceInterface;

interface Przelewy24CustomerInterface extends ResourceInterface
{
    /**
     * @return Collection<Przelewy24CreditCardInterface>
     */
    public function getCreditCards(): Collection;

    public function addCreditCard(Przelewy24CreditCardInterface $creditCard);

    public function getSyliusCustomer(): ?CustomerInterface;

    public function setSyliusCustomer(?CustomerInterface $syliusCustomer): void;
}
