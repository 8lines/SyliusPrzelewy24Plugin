<?php

declare(strict_types=1);

namespace BitBag\SyliusPrzelewy24Plugin\Subscription\Counter;

use BitBag\SyliusPrzelewy24Plugin\Subscription\Entity\SubscriptionIntervalInterface;

interface SubscriptionIntervalFailureCounterInterface
{
    public function incrementFailsCount(SubscriptionIntervalInterface $interval): void;

    public function resetFailsCount(SubscriptionIntervalInterface $interval): void;
}
