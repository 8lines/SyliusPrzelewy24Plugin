<?php

declare(strict_types=1);

namespace BitBag\SyliusPrzelewy24Plugin\Subscription\StateResolver;

use BitBag\SyliusPrzelewy24Plugin\Subscription\Repository\Przelewy24SubscriptionRepositoryInterface;
use BitBag\SyliusPrzelewy24Plugin\Subscription\Transition\Przelewy24SubscriptionTransition;
use SM\Factory\Factory;
use SM\SMException;

final class Przelewy24SubscriptionStateResolver implements Przelewy24SubscriptionStateResolverInterface
{
    public function __construct(
        private readonly Factory $stateMachineFactory,
        private readonly Przelewy24SubscriptionRepositoryInterface $przelewy24SubscriptionRepository,
    ) {
    }

    /**
     * @throws SMException
     */
    public function resolve(mixed $subscription): void
    {
        $subscriptionGraph = $this->stateMachineFactory->get(
            object: $subscription,
            graph: Przelewy24SubscriptionTransition::GRAPH,
        );

        $possibleTransitions = $subscriptionGraph->getPossibleTransitions();

        $transitions = [
            Przelewy24SubscriptionTransition::TRANSITION_ABORT,
            Przelewy24SubscriptionTransition::TRANSITION_COMPLETE,
        ];

        foreach ($transitions as $transition) {
            if (false === \in_array($transition, $possibleTransitions)) {
                continue;
            }

            $subscriptionGraph->apply(
                transition: $transition,
            );

            break;
        }

        $this->przelewy24SubscriptionRepository->add($subscription);
    }
}
