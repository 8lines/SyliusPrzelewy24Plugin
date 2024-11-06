<?php

declare(strict_types=1);

namespace BitBag\SyliusPrzelewy24Plugin\Subscription\Factory;

use BitBag\SyliusPrzelewy24Plugin\Subscription\Entity\Przelewy24SubscriptionConfigurationInterface;
use BitBag\SyliusPrzelewy24Plugin\Subscription\Entity\RecurringOrderInterface;
use Sylius\Resource\Factory\FactoryInterface;

/**
 * @extends FactoryInterface<Przelewy24SubscriptionConfigurationInterface>
 */
interface Przelewy24SubscriptionConfigurationFactoryInterface extends FactoryInterface
{
    public function createFromRecurringOrder(
        RecurringOrderInterface $recurringOrder,
    ): Przelewy24SubscriptionConfigurationInterface;
}
