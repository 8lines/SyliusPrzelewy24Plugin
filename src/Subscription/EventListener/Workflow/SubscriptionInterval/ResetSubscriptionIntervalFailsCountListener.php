<?php

declare(strict_types=1);

namespace BitBag\SyliusPrzelewy24Plugin\Subscription\EventListener\Workflow\SubscriptionInterval;

use BitBag\SyliusPrzelewy24Plugin\Subscription\Counter\SubscriptionIntervalFailureCounterInterface;
use BitBag\SyliusPrzelewy24Plugin\Subscription\Entity\SubscriptionIntervalInterface;
use Symfony\Component\Workflow\Event\CompletedEvent;

final readonly class ResetSubscriptionIntervalFailsCountListener
{
    public function __construct(
        private SubscriptionIntervalFailureCounterInterface $subscriptionIntervalFailureCounter,
    ) {
    }

    public function __invoke(CompletedEvent $event): void
    {
        /** @var SubscriptionIntervalInterface $interval */
        $interval = $event->getSubject();

        $this->subscriptionIntervalFailureCounter->resetFailsCount($interval);
    }
}
