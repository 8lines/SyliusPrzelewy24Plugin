<?php

declare(strict_types=1);

namespace BitBag\SyliusPrzelewy24Plugin\Subscription\Entity;

use Sylius\Component\Core\Model\CustomerInterface as BaseCustomerInterface;

interface SyliusCustomerAsSubscriberInterface extends BaseCustomerInterface
{
    public function getPrzelewy24Subscriber(): SubscriberInterface;

    public function setPrzelewy24Subscriber(SubscriberInterface $przelewy24Subscriber): void;
}
