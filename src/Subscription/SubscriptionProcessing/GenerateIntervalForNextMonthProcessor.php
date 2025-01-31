<?php

declare(strict_types=1);

namespace BitBag\SyliusPrzelewy24Plugin\Subscription\SubscriptionProcessing;

use BitBag\SyliusPrzelewy24Plugin\Subscription\Entity\SubscriptionInterface;

final readonly class GenerateIntervalForNextMonthProcessor implements SubscriptionProcessorInterface
{
    public function process(SubscriptionInterface $subscription): void
    {
        if (null !== $subscription->getConfiguration()->getRecurringTimes()) {
            return;
        }

        $schedule = $subscription->getSchedule();

        $lastInterval = $schedule->getLastInterval();
    }
}
