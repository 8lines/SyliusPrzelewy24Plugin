<?php

declare(strict_types=1);

namespace BitBag\SyliusPrzelewy24Plugin\Subscription\Factory;

use BitBag\SyliusPrzelewy24Plugin\Subscription\Entity\Przelewy24SubscriptionScheduleInterface;
use Sylius\Resource\Factory\FactoryInterface;

final class Przelewy24SubscriptionScheduleFactory implements Przelewy24SubscriptionScheduleFactoryInterface
{
    public function __construct(
        private readonly FactoryInterface $decoratedFactory,
    ) {
    }

    public function createNew(): Przelewy24SubscriptionScheduleInterface
    {
        return $this->decoratedFactory->createNew();
    }
}
