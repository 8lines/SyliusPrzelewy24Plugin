<?php

declare(strict_types=1);

namespace BitBag\SyliusPrzelewy24Plugin\Subscription\SubscriptionProcessing;

use BitBag\SyliusPrzelewy24Plugin\Subscription\Entity\SubscriptionInterface;
use Laminas\Stdlib\PriorityQueue;

final readonly class CompositeActiveSubscriptionProcessor implements SubscriptionProcessorInterface
{
    /** @var PriorityQueue<SubscriptionProcessorInterface> */
    private PriorityQueue $activeSubscriptionProcessors;

    public function __construct()
    {
        $this->activeSubscriptionProcessors = new PriorityQueue();
    }

    public function addProcessor(
        SubscriptionProcessorInterface $activeSubscriptionProcessor,
        int $priority,
    ): void {
        $this->activeSubscriptionProcessors->insert(
            data: $activeSubscriptionProcessor,
            priority: $priority,
        );
    }

    public function process(SubscriptionInterface $subscription): void
    {
        /** @var SubscriptionProcessorInterface $activeSubscriptionProcessor */
        foreach ($this->activeSubscriptionProcessors as $activeSubscriptionProcessor) {
            $activeSubscriptionProcessor->process($subscription);
        }
    }
}
