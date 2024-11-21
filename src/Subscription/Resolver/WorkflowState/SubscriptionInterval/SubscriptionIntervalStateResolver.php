<?php

declare(strict_types=1);

namespace BitBag\SyliusPrzelewy24Plugin\Subscription\Resolver\WorkflowState\SubscriptionInterval;

use BitBag\SyliusPrzelewy24Plugin\Subscription\Entity\SubscriptionIntervalInterface;
use BitBag\SyliusPrzelewy24Plugin\Subscription\Repository\SubscriptionIntervalRepositoryInterface;
use BitBag\SyliusPrzelewy24Plugin\Subscription\Transitions\SubscriptionIntervalTransitions;
use Sylius\Abstraction\StateMachine\StateMachineInterface;
use Sylius\Abstraction\StateMachine\TransitionInterface;

final readonly class SubscriptionIntervalStateResolver implements SubscriptionIntervalStateResolverInterface
{
    public function __construct(
        private StateMachineInterface $stateMachine,
        private SubscriptionIntervalRepositoryInterface $subscriptionIntervalRepository,
    ) {
    }

    public function resolve(SubscriptionIntervalInterface $interval): void
    {
        $possibleTransitions = $this->stateMachine->getEnabledTransitions(
            subject: $interval,
            graphName: SubscriptionIntervalTransitions::GRAPH,
        );

        $possibleTransitionsNames = array_map(
            callback: fn(TransitionInterface $transition): string => $transition->getName(),
            array: $possibleTransitions,
        );

        $transitions = [
            SubscriptionIntervalTransitions::TRANSITION_ABORT,
            SubscriptionIntervalTransitions::TRANSITION_COMPLETE,
            SubscriptionIntervalTransitions::TRANSITION_ACTIVATE,
            SubscriptionIntervalTransitions::TRANSITION_AWAIT_PAYMENT,
            SubscriptionIntervalTransitions::TRANSITION_SCHEDULE,
        ];

        /** @var string $transition */
        foreach ($transitions as $transition) {
            if (false === \in_array($transition, $possibleTransitionsNames)) {
                continue;
            }

            $this->stateMachine->apply(
                subject: $interval,
                graphName: SubscriptionIntervalTransitions::GRAPH,
                transition: $transition,
            );

            break;
        }

        $this->subscriptionIntervalRepository->add($interval);
    }
}
