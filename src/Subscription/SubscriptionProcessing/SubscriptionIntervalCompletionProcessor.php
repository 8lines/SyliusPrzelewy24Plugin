<?php

declare(strict_types=1);

namespace BitBag\SyliusPrzelewy24Plugin\Subscription\SubscriptionProcessing;

use BitBag\SyliusPrzelewy24Plugin\Subscription\Entity\SubscriptionInterface;
use BitBag\SyliusPrzelewy24Plugin\Subscription\Repository\SubscriptionRepositoryInterface;
use BitBag\SyliusPrzelewy24Plugin\Subscription\Transitions\SubscriptionIntervalTransitions;
use Sylius\Abstraction\StateMachine\StateMachineInterface;

final readonly class SubscriptionIntervalCompletionProcessor implements SubscriptionProcessorInterface
{
    public function __construct(
        private StateMachineInterface $stateMachine,
        private SubscriptionRepositoryInterface $subscriptionRepository,
    ) {
    }

    public function process(SubscriptionInterface $subscription): void
    {
        $schedule = $subscription->getSchedule();
        $interval = $schedule->getCurrentInterval();

        if (null === $interval) {
            return;
        }

        $intervalCompleted = $this->stateMachine->can(
            subject: $interval,
            graphName: SubscriptionIntervalTransitions::GRAPH,
            transition: SubscriptionIntervalTransitions::TRANSITION_COMPLETE,
        );

        if (false === $intervalCompleted) {
            return;
        }

        $this->stateMachine->apply(
            subject: $interval,
            graphName: SubscriptionIntervalTransitions::GRAPH,
            transition: SubscriptionIntervalTransitions::TRANSITION_COMPLETE,
        );

        $subscription->getSchedule()->incrementCurrentSequence();

        $this->subscriptionRepository->add($subscription);
    }
}
