<?php

declare(strict_types=1);

namespace BitBag\SyliusPrzelewy24Plugin\Subscription\Entity;

use Sylius\Component\Core\Model\CustomerInterface as BaseCustomerInterface;

interface CustomerInterface extends BaseCustomerInterface
{
    public function getPrzelewy24Customer(): Przelewy24CustomerInterface;

    public function setPrzelewy24Customer(Przelewy24CustomerInterface $przelewy24Customer): void;
}
