<?php

declare(strict_types=1);

namespace BitBag\SyliusPrzelewy24Plugin\Subscription\StateGuard;

use BitBag\SyliusPrzelewy24Plugin\Subscription\Entity\Przelewy24SubscriptionScheduleIntervalInterface;
use Sylius\Component\Core\Model\PaymentInterface;
use Symfony\Component\Clock\ClockInterface;

final class IsPrzelewy24SubscriptionScheduleIntervalPaidAndExpiredGuard implements Przelewy24SubscriptionScheduleIntervalGuardInterface
{
    public function __construct(
        private readonly ClockInterface $clock,
    ) {
    }

    public function isEligible(Przelewy24SubscriptionScheduleIntervalInterface $interval): bool
    {
        if (false === $interval->isPaid()) {
            return false;
        }

        if (null === $interval->getEndsAt()) {
            return false;
        }

        if (false === $interval->getEndsAt() < $this->clock->now()) {
            return false;
        }

        return true;
    }
}
