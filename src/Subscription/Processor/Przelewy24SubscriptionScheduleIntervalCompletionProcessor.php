<?php

declare(strict_types=1);

namespace BitBag\SyliusPrzelewy24Plugin\Subscription\Processor;

use BitBag\SyliusPrzelewy24Plugin\Subscription\Entity\Przelewy24SubscriptionInterface;
use BitBag\SyliusPrzelewy24Plugin\Subscription\Repository\Przelewy24SubscriptionRepositoryInterface;
use BitBag\SyliusPrzelewy24Plugin\Subscription\Transition\Przelewy24SubscriptionScheduleIntervalTransition;
use Psr\Log\LoggerInterface;
use SM\Factory\Factory;
use SM\SMException;

final class Przelewy24SubscriptionScheduleIntervalCompletionProcessor implements Przelewy24SubscriptionScheduleIntervalCompletionProcessorInterface
{
    public function __construct(
        private readonly Factory $stateMachineFactory,
        private readonly Przelewy24SubscriptionRepositoryInterface $przelewy24SubscriptionRepository,
        private readonly LoggerInterface $logger,
    ) {
    }

    public function process(): void
    {
        $activeSubscriptions = $this->przelewy24SubscriptionRepository->findActiveSubscriptions();

        foreach ($activeSubscriptions as $subscription) {
            try {
                $this->completeCurrentIntervalIfPaidAndExpired($subscription);
            } catch (\Exception $exception) {
                $this->logger->error($exception->getMessage());
            }
        }
    }

    /**
     * @throws SMException
     */
    public function completeCurrentIntervalIfPaidAndExpired(Przelewy24SubscriptionInterface $subscription): void
    {
        $schedule = $subscription->getSchedule();
        $interval = $schedule->getCurrentInterval();

        if (null === $interval) {
            return;
        }

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
