<?php

declare(strict_types=1);

namespace BitBag\SyliusPrzelewy24Plugin\Subscription\Guard\WorkflowState\Subscription;

use Symfony\Component\Workflow\Event\GuardEvent;

interface SubscriptionGuardInterface
{
    public function guardReview(GuardEvent $event): void;
}
