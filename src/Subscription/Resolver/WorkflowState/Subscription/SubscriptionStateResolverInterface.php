<?php

declare(strict_types=1);

namespace BitBag\SyliusPrzelewy24Plugin\Subscription\Resolver\WorkflowState\Subscription;

use BitBag\SyliusPrzelewy24Plugin\Subscription\Entity\SubscriptionInterface;

interface SubscriptionStateResolverInterface
{
    public function resolve(SubscriptionInterface $subscription): void;
}
