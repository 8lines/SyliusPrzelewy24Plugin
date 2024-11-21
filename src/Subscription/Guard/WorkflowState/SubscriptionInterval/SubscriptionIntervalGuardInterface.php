<?php

declare(strict_types=1);

namespace BitBag\SyliusPrzelewy24Plugin\Subscription\Guard\WorkflowState\SubscriptionInterval;

use Symfony\Component\Workflow\Event\GuardEvent;

interface SubscriptionIntervalGuardInterface
{
    public function guardReview(GuardEvent $event): void;
}
