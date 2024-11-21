<?php

declare(strict_types=1);

namespace BitBag\SyliusPrzelewy24Plugin\Subscription\Creator;

use BitBag\SyliusPrzelewy24Plugin\Subscription\Entity\RecurringSyliusOrderInterface;
use BitBag\SyliusPrzelewy24Plugin\Subscription\Factory\SubscriptionFactoryInterface;
use BitBag\SyliusPrzelewy24Plugin\Subscription\Repository\SubscriptionRepositoryInterface;

final readonly class SubscriptionCreator implements SubscriptionCreatorInterface
{
    public function __construct(
        private SubscriptionFactoryInterface $subscriptionFactory,
        private SubscriptionRepositoryInterface $subscriptionRepository,

    ) {
    }

    public function createFromOrder(RecurringSyliusOrderInterface $order): void
    {
        $subscription = $this->subscriptionFactory->createFromOrder(
            order: $order,
        );

        $order->getRecurringPrzelewy24Order()->setSubscription($subscription);

        $this->subscriptionRepository->add($subscription);
    }
}
