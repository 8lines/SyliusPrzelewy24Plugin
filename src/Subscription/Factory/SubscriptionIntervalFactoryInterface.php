<?php

declare(strict_types=1);

namespace BitBag\SyliusPrzelewy24Plugin\Subscription\Factory;

use BitBag\SyliusPrzelewy24Plugin\Subscription\Entity\SubscriptionIntervalInterface;
use Sylius\Resource\Factory\FactoryInterface;

/**
 * @extends FactoryInterface<SubscriptionIntervalInterface>
 */
interface SubscriptionIntervalFactoryInterface extends FactoryInterface
{
    public function createForPeriod(
        int $sequence,
        \DateTimeImmutable $from,
        \DateTimeImmutable $to,
    ): SubscriptionIntervalInterface;
}
