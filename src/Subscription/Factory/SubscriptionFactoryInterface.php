<?php

declare(strict_types=1);

namespace BitBag\SyliusPrzelewy24Plugin\Subscription\Factory;

use BitBag\SyliusPrzelewy24Plugin\Subscription\Entity\SubscriptionInterface;
use BitBag\SyliusPrzelewy24Plugin\Subscription\Entity\RecurringSyliusOrderInterface;
use Sylius\Resource\Factory\FactoryInterface;

/**
 * @extends FactoryInterface<SubscriptionInterface>
 */
interface SubscriptionFactoryInterface extends FactoryInterface
{
    public function createFromOrder(RecurringSyliusOrderInterface $order): SubscriptionInterface;
}
