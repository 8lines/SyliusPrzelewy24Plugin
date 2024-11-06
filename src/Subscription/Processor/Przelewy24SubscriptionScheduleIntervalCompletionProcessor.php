<?php

declare(strict_types=1);

namespace BitBag\SyliusPrzelewy24Plugin\Subscription\Processor;

use BitBag\SyliusPrzelewy24Plugin\Subscription\Entity\Przelewy24SubscriptionInterface;
use BitBag\SyliusPrzelewy24Plugin\Subscription\Entity\Przelewy24SubscriptionScheduleIntervalInterface;
use BitBag\SyliusPrzelewy24Plugin\Subscription\Repository\Przelewy24SubscriptionRepositoryInterface;
use BitBag\SyliusPrzelewy24Plugin\Subscription\Transition\Przelewy24SubscriptionScheduleIntervalTransition;
use SM\Factory\Factory;
use SM\SMException;

final class Przelewy24SubscriptionScheduleIntervalCompletionProcessor implements Przelewy24SubscriptionScheduleIntervalCompletionProcessorInterface
{
    public function __construct(
        private readonly Factory $stateMachineFactory,
        private readonly Przelewy24SubscriptionRepositoryInterface $przelewy24SubscriptionRepository,
    ) {
    }

    /**
     * @throws SMException
     */
    public function process(): void
    {
        $activeSubscriptions = $this->przelewy24SubscriptionRepository->findActiveSubscriptions();

        foreach ($activeSubscriptions as $subscription) {
            $schedule = $subscription->getSchedule();
            $currentInterval = $schedule->getCurrentInterval();

            if (null === $currentInterval) {
                continue;
            }

            $this->completeIntervalIfPaidAndExpired(
                subscription: $subscription,
                interval: $currentInterval,
            );
        }
    }

    /**
     * @throws SMException
     */
    public function completeIntervalIfPaidAndExpired(
        Przelewy24SubscriptionInterface $subscription,
        Przelewy24SubscriptionScheduleIntervalInterface $interval,
    ): void {
        $intervalGraph = $this->stateMachineFactory->get(
            object: $interval,
            graph: Przelewy24SubscriptionScheduleIntervalTransition::GRAPH,
        );

        $intervalCompleted = $intervalGraph->can(
            transition: Przelewy24SubscriptionScheduleIntervalTransition::TRANSITION_COMPLETE,
        );

        if (false === $intervalCompleted) {
            return;
        }

        $intervalGraph->apply(
            transition: Przelewy24SubscriptionScheduleIntervalTransition::TRANSITION_COMPLETE,
        );

        $subscription->getSchedule()->incrementCurrentSequence();

        $this->przelewy24SubscriptionRepository->add($subscription);
    }
}
