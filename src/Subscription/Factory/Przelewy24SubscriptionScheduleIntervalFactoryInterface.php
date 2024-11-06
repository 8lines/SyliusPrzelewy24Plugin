<?php

declare(strict_types=1);

namespace BitBag\SyliusPrzelewy24Plugin\Subscription\Factory;

use BitBag\SyliusPrzelewy24Plugin\Subscription\Entity\Przelewy24SubscriptionScheduleIntervalInterface;
use Sylius\Resource\Factory\FactoryInterface;

/**
 * @extends FactoryInterface<Przelewy24SubscriptionScheduleIntervalInterface>
 */
interface Przelewy24SubscriptionScheduleIntervalFactoryInterface extends FactoryInterface
{
    public function createForPeriod(
        int $sequence,
        \DateTimeImmutable $from,
        \DateTimeImmutable $to,
    ): Przelewy24SubscriptionScheduleIntervalInterface;
}
