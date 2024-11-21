<?php

declare(strict_types=1);

namespace BitBag\SyliusPrzelewy24Plugin\Subscription\Generator;

use BitBag\SyliusPrzelewy24Plugin\Subscription\Entity\SubscriptionScheduleInterface;

interface SubscriptionScheduleGeneratorInterface
{
    public function generate(
        \DateTimeImmutable $startsAt,
        int $recurringTimes,
        int $recurringIntervalInDays,
    ): SubscriptionScheduleInterface;
}
