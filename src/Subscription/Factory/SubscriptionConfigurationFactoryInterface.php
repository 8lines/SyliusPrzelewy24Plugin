<?php

declare(strict_types=1);

namespace BitBag\SyliusPrzelewy24Plugin\Subscription\Factory;

use BitBag\SyliusPrzelewy24Plugin\Subscription\Entity\SubscriptionConfigurationInterface;
use BitBag\SyliusPrzelewy24Plugin\Subscription\Entity\RecurringSyliusOrderInterface;
use Sylius\Resource\Factory\FactoryInterface;

/**
 * @extends FactoryInterface<SubscriptionConfigurationInterface>
 */
interface SubscriptionConfigurationFactoryInterface extends FactoryInterface
{
    public function createFromOrder(RecurringSyliusOrderInterface $order): SubscriptionConfigurationInterface;
}
