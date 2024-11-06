<?php

declare(strict_types=1);

namespace BitBag\SyliusPrzelewy24Plugin\Subscription\Counter;

use BitBag\SyliusPrzelewy24Plugin\Subscription\Entity\Przelewy24SubscriptionScheduleIntervalInterface;

interface Przelewy24SubscriptionScheduleIntervalFailureCounterInterface
{
    public function incrementFailsCount(Przelewy24SubscriptionScheduleIntervalInterface $interval): void;

    public function resetFailsCount(Przelewy24SubscriptionScheduleIntervalInterface $interval): void;
}
