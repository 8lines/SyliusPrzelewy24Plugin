<?php

declare(strict_types=1);

namespace BitBag\SyliusPrzelewy24Plugin\Subscription\Factory;

use BitBag\SyliusPrzelewy24Plugin\Subscription\Entity\Przelewy24SubscriptionInterface;
use BitBag\SyliusPrzelewy24Plugin\Subscription\Entity\RecurringOrderInterface;
use Sylius\Resource\Factory\FactoryInterface;

/**
 * @extends FactoryInterface<Przelewy24SubscriptionInterface>
 */
interface Przelewy24SubscriptionFactoryInterface extends FactoryInterface
{
    public function createFromRecurringOrder(
        RecurringOrderInterface $recurringOrder,
    ): Przelewy24SubscriptionInterface;
}
