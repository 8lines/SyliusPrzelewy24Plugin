<?php

declare(strict_types=1);

namespace BitBag\SyliusPrzelewy24Plugin\Subscription\Generator;

use BitBag\SyliusPrzelewy24Plugin\Subscription\Entity\SubscriptionScheduleInterface;
use BitBag\SyliusPrzelewy24Plugin\Subscription\Factory\SubscriptionScheduleFactoryInterface;
use BitBag\SyliusPrzelewy24Plugin\Subscription\Factory\SubscriptionIntervalFactoryInterface;

final readonly class SubscriptionScheduleGenerator implements SubscriptionScheduleGeneratorInterface
{
    public function __construct(
        private SubscriptionScheduleFactoryInterface $subscriptionScheduleFactory,
        private SubscriptionIntervalFactoryInterface $subscriptionIntervalFactory,
    ) {
    }

    /**
     * @throws \DateMalformedStringException
     */
    public function generate(
        \DateTimeImmutable $startsAt,
        int $recurringTimes,
        int $recurringIntervalInDays,
    ): SubscriptionScheduleInterface {
        $schedule = $this->subscriptionScheduleFactory->createNew();

        for ($sequence = 0; $sequence < $recurringTimes; $sequence++) {
            $daysFromBeginning = $sequence * $recurringIntervalInDays;

            $interval = $this->subscriptionIntervalFactory->createForPeriod(
                sequence: $sequence,
                from: $startsAt->modify('+' . $daysFromBeginning . ' days'),
                to: $startsAt->modify('+' . ($daysFromBeginning + $recurringIntervalInDays) . ' days'),
            );

            $schedule->addInterval($interval);
        }

        return $schedule;
    }
}
