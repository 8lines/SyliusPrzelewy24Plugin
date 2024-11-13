<?php

declare(strict_types=1);

namespace BitBag\SyliusPrzelewy24Plugin\Subscription\StateGuard;

use BitBag\SyliusPrzelewy24Plugin\Subscription\Entity\Przelewy24SubscriptionScheduleIntervalInterface;
use Symfony\Component\Clock\ClockInterface;

final class IsPrzelewy24SubscriptionScheduleIntervalStartsAtReachedGuard implements Przelewy24SubscriptionScheduleIntervalGuardInterface
{
    public function __construct(
        private readonly ClockInterface $clock,
    ) {
    }

    public function isEligible(Przelewy24SubscriptionScheduleIntervalInterface $interval): bool
    {
        if (null === $interval->getStartsAt()) {
            return false;
        }

        if (false === $interval->getStartsAt() < $this->clock->now()) {
            return false;
        }

        return true;
    }
}
