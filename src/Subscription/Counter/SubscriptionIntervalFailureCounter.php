<?php

declare(strict_types=1);

namespace BitBag\SyliusPrzelewy24Plugin\Subscription\Counter;

use BitBag\SyliusPrzelewy24Plugin\Subscription\Entity\SubscriptionIntervalInterface;
use BitBag\SyliusPrzelewy24Plugin\Subscription\Repository\SubscriptionIntervalRepositoryInterface;

final readonly class SubscriptionIntervalFailureCounter implements SubscriptionIntervalFailureCounterInterface
{
    public function __construct(
        private SubscriptionIntervalRepositoryInterface $subscriptionIntervalRepository,
    ) {
    }

    public function incrementFailsCount(SubscriptionIntervalInterface $interval): void
    {
        $interval->incrementFailedPaymentAttempts();

        $this->subscriptionIntervalRepository->add($interval);
    }

    public function resetFailsCount(SubscriptionIntervalInterface $interval): void
    {
        $interval->resetFailedPaymentAttempts();

        $this->subscriptionIntervalRepository->add($interval);
    }
}
