<?php

declare(strict_types=1);

namespace BitBag\SyliusPrzelewy24Plugin\Subscription\Factory;

use BitBag\SyliusPrzelewy24Plugin\Subscription\Entity\Przelewy24SubscriptionScheduleIntervalInterface;
use Sylius\Resource\Factory\FactoryInterface;

final class Przelewy24SubscriptionScheduleIntervalFactory implements Przelewy24SubscriptionScheduleIntervalFactoryInterface
{
    public function __construct(
        private readonly FactoryInterface $decoratedFactory,
    ) {
    }

    public function createNew(): Przelewy24SubscriptionScheduleIntervalInterface
    {
        return $this->decoratedFactory->createNew();
    }

    public function createForPeriod(
        int $sequence,
        \DateTimeImmutable $from,
        \DateTimeImmutable $to,
    ): Przelewy24SubscriptionScheduleIntervalInterface {
        $interval = $this->createNew();

        $interval->setSequence($sequence);
        $interval->setStartsAt($from);
        $interval->setEndsAt($to);

        return $interval;
    }
}
