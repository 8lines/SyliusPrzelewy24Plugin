<?php

declare(strict_types=1);

namespace BitBag\SyliusPrzelewy24Plugin\Subscription\SubscriptionProcessing;

use BitBag\SyliusPrzelewy24Plugin\Subscription\Repository\SubscriptionRepositoryInterface;

final readonly class ActiveSubscriptionsProcessor implements ActiveSubscriptionsProcessorInterface
{
    public function __construct(
        private SubscriptionRepositoryInterface $subscriptionRepository,
        private SubscriptionProcessorInterface $compositeActiveSubscriptionProcessor,
    ) {
    }

    public function processActiveSubscriptions(): void
    {
        $activeSubscriptions = $this->subscriptionRepository->findActiveSubscriptions();

        foreach ($activeSubscriptions as $subscription) {
            try {
                $this->compositeActiveSubscriptionProcessor->process($subscription);
            } catch (\Exception $e) {
                dump ($e);
            }
        }
    }
}
