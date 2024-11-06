<?php

declare(strict_types=1);

namespace BitBag\SyliusPrzelewy24Plugin\Subscription\Counter;

use BitBag\SyliusPrzelewy24Plugin\Subscription\Entity\Przelewy24SubscriptionScheduleIntervalInterface;
use BitBag\SyliusPrzelewy24Plugin\Subscription\Repository\Przelewy24SubscriptionScheduleIntervalRepositoryInterface;

final class Przelewy24SubscriptionScheduleIntervalFailureCounter implements Przelewy24SubscriptionScheduleIntervalFailureCounterInterface
{
    public function __construct(
        private readonly Przelewy24SubscriptionScheduleIntervalRepositoryInterface $przelewy24SubscriptionScheduleIntervalRepository,
    ) {
    }

    public function incrementFailsCount(Przelewy24SubscriptionScheduleIntervalInterface $interval): void
    {
        $interval->incrementFailedPaymentAttempts();

        $this->przelewy24SubscriptionScheduleIntervalRepository->add($interval);
    }

    public function resetFailsCount(Przelewy24SubscriptionScheduleIntervalInterface $interval): void
    {
        $interval->resetFailedPaymentAttempts();

        $this->przelewy24SubscriptionScheduleIntervalRepository->add($interval);
    }
}
