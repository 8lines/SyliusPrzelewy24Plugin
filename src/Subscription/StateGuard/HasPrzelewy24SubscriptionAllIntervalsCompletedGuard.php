<?php

declare(strict_types=1);

namespace BitBag\SyliusPrzelewy24Plugin\Subscription\StateGuard;

use BitBag\SyliusPrzelewy24Plugin\Subscription\Entity\Przelewy24SubscriptionInterface;

final class HasPrzelewy24SubscriptionAllIntervalsCompletedGuard implements Przelewy24SubscriptionGuardInterface
{
    public function isEligible(Przelewy24SubscriptionInterface $subscription): bool
    {
        foreach ($subscription->getSchedule()->getIntervals() as $interval) {
            if (false === $interval->isCompleted()) {
                return false;
            }
        }

        return true;
    }
}
