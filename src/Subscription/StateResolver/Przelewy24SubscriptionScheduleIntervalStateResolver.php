<?php

declare(strict_types=1);

namespace BitBag\SyliusPrzelewy24Plugin\Subscription\StateResolver;

use BitBag\SyliusPrzelewy24Plugin\Subscription\Entity\Przelewy24SubscriptionScheduleIntervalInterface;
use BitBag\SyliusPrzelewy24Plugin\Subscription\Repository\Przelewy24SubscriptionScheduleIntervalRepositoryInterface;
use BitBag\SyliusPrzelewy24Plugin\Subscription\Transition\Przelewy24SubscriptionScheduleIntervalTransition;
use SM\Factory\Factory;
use SM\SMException;

final class Przelewy24SubscriptionScheduleIntervalStateResolver implements Przelewy24SubscriptionScheduleIntervalStateResolverInterface
{
    public function __construct(
        private readonly Factory $stateMachineFactory,
        private readonly Przelewy24SubscriptionScheduleIntervalRepositoryInterface $przelewy24SubscriptionScheduleIntervalRepository,
    ) {
    }

    /**
     * @throws SMException
     */
    public function resolve(Przelewy24SubscriptionScheduleIntervalInterface $interval): void
    {
        $intervalGraph = $this->stateMachineFactory->get(
            object: $interval,
            graph: Przelewy24SubscriptionScheduleIntervalTransition::GRAPH,
        );

        $possibleTransitions = $intervalGraph->getPossibleTransitions();

        $transitions = [
            Przelewy24SubscriptionScheduleIntervalTransition::TRANSITION_ABORT,
            Przelewy24SubscriptionScheduleIntervalTransition::TRANSITION_COMPLETE,
            Przelewy24SubscriptionScheduleIntervalTransition::TRANSITION_ACTIVATE,
            Przelewy24SubscriptionScheduleIntervalTransition::TRANSITION_AWAIT_PAYMENT,
            Przelewy24SubscriptionScheduleIntervalTransition::TRANSITION_SCHEDULE,
        ];

        foreach ($transitions as $transition) {
            if (false === \in_array($transition, $possibleTransitions)) {
                continue;
            }

            $intervalGraph->apply(
                transition: $transition,
            );

            break;
        }

        $this->przelewy24SubscriptionScheduleIntervalRepository->add($interval);
    }
}
