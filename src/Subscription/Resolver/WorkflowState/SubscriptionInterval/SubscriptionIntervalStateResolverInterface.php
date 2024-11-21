<?php

declare(strict_types=1);

namespace BitBag\SyliusPrzelewy24Plugin\Subscription\Resolver\WorkflowState\SubscriptionInterval;

use BitBag\SyliusPrzelewy24Plugin\Subscription\Entity\SubscriptionIntervalInterface;

interface SubscriptionIntervalStateResolverInterface
{
    public function resolve(SubscriptionIntervalInterface $interval): void;
}
