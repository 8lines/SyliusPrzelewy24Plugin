<?php

declare(strict_types=1);

namespace BitBag\SyliusPrzelewy24Plugin\Subscription\Factory;

use BitBag\SyliusPrzelewy24Plugin\Subscription\Entity\SubscriptionScheduleInterface;
use Sylius\Resource\Factory\FactoryInterface;

final readonly class SubscriptionScheduleFactory implements SubscriptionScheduleFactoryInterface
{
    public function __construct(
        private FactoryInterface $decoratedFactory,
    ) {
    }

    public function createNew(): SubscriptionScheduleInterface
    {
        return $this->decoratedFactory->createNew();
    }
}
