<?php

declare(strict_types=1);

namespace BitBag\SyliusPrzelewy24Plugin\Subscription\Factory;

use BitBag\SyliusPrzelewy24Plugin\Subscription\Entity\SubscriptionIntervalInterface;
use Sylius\Resource\Factory\FactoryInterface;

final readonly class SubscriptionIntervalFactory implements SubscriptionIntervalFactoryInterface
{
    public function __construct(
        private FactoryInterface $decoratedFactory,
    ) {
    }

    public function createNew(): SubscriptionIntervalInterface
    {
        return $this->decoratedFactory->createNew();
    }

    public function createForPeriod(
        int $sequence,
        \DateTimeImmutable $from,
        \DateTimeImmutable $to,
    ): SubscriptionIntervalInterface {
        $interval = $this->createNew();

        $interval->setSequence($sequence);
        $interval->setStartsAt($from);
        $interval->setEndsAt($to);

        return $interval;
    }
}
