<?php

declare(strict_types=1);

namespace BitBag\SyliusPrzelewy24Plugin\Subscription\Generator;

use BitBag\SyliusPrzelewy24Plugin\Subscription\Entity\Przelewy24SubscriptionScheduleInterface;
use BitBag\SyliusPrzelewy24Plugin\Subscription\Factory\Przelewy24SubscriptionScheduleFactoryInterface;
use BitBag\SyliusPrzelewy24Plugin\Subscription\Factory\Przelewy24SubscriptionScheduleIntervalFactoryInterface;

final class Przelewy24SubscriptionScheduleGenerator implements Przelewy24SubscriptionScheduleGeneratorInterface
{
    public function __construct(
        private readonly Przelewy24SubscriptionScheduleFactoryInterface $przelewy24SubscriptionScheduleFactory,
        private readonly Przelewy24SubscriptionScheduleIntervalFactoryInterface $przelewy24SubscriptionScheduleIntervalFactory,
    ) {
    }

    public function generate(
        \DateTimeImmutable $startsAt,
        int $recurringTimes,
        int $recurringIntervalInDays,
    ): Przelewy24SubscriptionScheduleInterface {
        $schedule = $this->przelewy24SubscriptionScheduleFactory->createNew();

        for ($sequence = 0; $sequence < $recurringTimes; $sequence++) {
            $daysFromBeginning = $sequence * $recurringIntervalInDays;

            $interval = $this->przelewy24SubscriptionScheduleIntervalFactory->createForPeriod(
                sequence: $sequence,
                from: $startsAt->modify('+' . $daysFromBeginning . ' days'),
                to: $startsAt->modify('+' . ($daysFromBeginning + $recurringIntervalInDays) . ' days'),
            );

            $schedule->addInterval($interval);
        }

        return $schedule;
    }
}
