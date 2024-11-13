<?php

declare(strict_types=1);

namespace BitBag\SyliusPrzelewy24Plugin\Subscription\Processor;

use BitBag\SyliusPrzelewy24Plugin\Subscription\Entity\Przelewy24SubscriptionInterface;
use BitBag\SyliusPrzelewy24Plugin\Subscription\Repository\Przelewy24SubscriptionRepositoryInterface;
use BitBag\SyliusPrzelewy24Plugin\Subscription\Transition\Przelewy24SubscriptionScheduleIntervalTransition;
use Psr\Log\LoggerInterface;
use SM\Factory\Factory;
use SM\SMException;

final class Przelewy24SubscriptionAwaitingPaymentsProcessor implements Przelewy24SubscriptionAwaitingPaymentsProcessorInterface
{
    public function __construct(
        private readonly Factory $stateMachineFactory,
        private readonly Przelewy24SubscriptionRepositoryInterface $przelewy24SubscriptionRepository,
        private readonly Przelewy24SubscriptionPaymentProcessorInterface $przelewy24SubscriptionPaymentProcessor,
        private readonly LoggerInterface $logger,
    ) {
    }

    public function process(): void
    {
        $activeSubscriptions = $this->przelewy24SubscriptionRepository->findActiveSubscriptions();

        foreach ($activeSubscriptions as $subscription) {
            try {
                $this->processPaymentForCurrentIntervalIfAwaitingPayment($subscription);
            } catch (\Exception $exception) {
                $this->logger->error($exception->getMessage());
            }
        }
    }

    /**
     * @throws SMException
     */
    private function processPaymentForCurrentIntervalIfAwaitingPayment(
        Przelewy24SubscriptionInterface $subscription,
    ): void {
        $interval = $subscription->getSchedule()->getCurrentInterval();

        if (null === $interval) {
            return;
        }

        $intervalGraph = $this->stateMachineFactory->get(
            object: $interval,
            graph: Przelewy24SubscriptionScheduleIntervalTransition::GRAPH,
        );

        $intervalAwaitingPayment = $intervalGraph->can(
            transition: Przelewy24SubscriptionScheduleIntervalTransition::TRANSITION_AWAIT_PAYMENT,
        );

        if (false === $intervalAwaitingPayment) {
            return;
        }

        $intervalGraph->apply(
            transition: Przelewy24SubscriptionScheduleIntervalTransition::TRANSITION_AWAIT_PAYMENT,
        );

        $this->przelewy24SubscriptionRepository->add($subscription);

        $this->przelewy24SubscriptionPaymentProcessor->processRecurringPayment(
            subscription: $subscription,
            sequence: $interval->getSequence(),
        );
    }
}
