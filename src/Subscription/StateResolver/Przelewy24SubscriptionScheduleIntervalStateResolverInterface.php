<?php

declare(strict_types=1);

namespace BitBag\SyliusPrzelewy24Plugin\Subscription\StateResolver;

use BitBag\SyliusPrzelewy24Plugin\Subscription\Entity\Przelewy24SubscriptionScheduleIntervalInterface;

interface Przelewy24SubscriptionScheduleIntervalStateResolverInterface
{
    public function resolve(Przelewy24SubscriptionScheduleIntervalInterface $interval): void;
}
