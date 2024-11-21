<?php

declare(strict_types=1);

namespace BitBag\SyliusPrzelewy24Plugin\Subscription\Resolver\WorkflowState\Subscription;

use BitBag\SyliusPrzelewy24Plugin\Subscription\Repository\SubscriptionRepositoryInterface;
use BitBag\SyliusPrzelewy24Plugin\Subscription\Transitions\SubscriptionTransitions;
use Sylius\Abstraction\StateMachine\StateMachineInterface;
use Sylius\Abstraction\StateMachine\TransitionInterface;

final readonly class SubscriptionStateResolver implements SubscriptionStateResolverInterface
{
    public function __construct(
        private StateMachineInterface $stateMachine,
        private SubscriptionRepositoryInterface $subscriptionRepository,
    ) {
    }

    public function resolve(mixed $subscription): void
    {
        $possibleTransitions = $this->stateMachine->getEnabledTransitions(
            subject: $subscription,
            graphName: SubscriptionTransitions::GRAPH,
        );

        $possibleTransitionsNames = array_map(
            callback: fn(TransitionInterface $transition): string => $transition->getName(),
            array: $possibleTransitions,
        );

        $transitions = [
            SubscriptionTransitions::TRANSITION_ABORT,
            SubscriptionTransitions::TRANSITION_COMPLETE,
        ];

        /** @var string $transition */
        foreach ($transitions as $transition) {
            if (false === \in_array($transition, $possibleTransitionsNames)) {
                continue;
            }

            $this->stateMachine->apply(
                subject: $subscription,
                graphName: SubscriptionTransitions::GRAPH,
                transition: $transition,
            );

            break;
        }

        $this->subscriptionRepository->add($subscription);
    }
}
