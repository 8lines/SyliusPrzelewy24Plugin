<?php

declare(strict_types=1);

namespace BitBag\SyliusPrzelewy24Plugin\Subscription\EventListener\Workflow\SubscriptionInterval;

use BitBag\SyliusPrzelewy24Plugin\Subscription\Entity\SubscriptionInterface;
use BitBag\SyliusPrzelewy24Plugin\Subscription\Entity\SubscriptionIntervalInterface;
use BitBag\SyliusPrzelewy24Plugin\Subscription\Entity\SubscriptionScheduleInterface;
use BitBag\SyliusPrzelewy24Plugin\Subscription\Resolver\WorkflowState\Subscription\SubscriptionStateResolverInterface;
use Symfony\Component\Workflow\Event\CompletedEvent;
use Webmozart\Assert\Assert;

final readonly class ResolveSubscriptionStateListener
{
    public function __construct(
        private SubscriptionStateResolverInterface $subscriptionIntervalStateResolver,
    ) {
    }

    public function __invoke(CompletedEvent $event): void
    {
        /** @var SubscriptionIntervalInterface $interval */
        $interval = $event->getSubject();

        /** @var SubscriptionScheduleInterface $schedule */
        $schedule = $interval->getSchedule();

        Assert::notNull(
            value: $schedule,
            message: 'Schedule cannot be null.'
        );

        /** @var SubscriptionInterface $subscription */
        $subscription = $schedule->getSubscription();

        Assert::notNull(
            value: $subscription,
            message: 'Subscription cannot be null when resolving its state.'
        );

        $this->subscriptionIntervalStateResolver->resolve($subscription);
    }
}
