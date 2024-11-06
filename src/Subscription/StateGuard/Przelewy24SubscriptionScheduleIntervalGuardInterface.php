<?php

declare(strict_types=1);

namespace BitBag\SyliusPrzelewy24Plugin\Subscription\StateGuard;

use BitBag\SyliusPrzelewy24Plugin\Subscription\Entity\Przelewy24SubscriptionScheduleIntervalInterface;

interface Przelewy24SubscriptionScheduleIntervalGuardInterface
{
    public function isEligible(Przelewy24SubscriptionScheduleIntervalInterface $interval): bool;
}
