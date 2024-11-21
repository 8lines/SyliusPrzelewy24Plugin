<?php

declare(strict_types=1);

namespace BitBag\SyliusPrzelewy24Plugin\Subscription\Applicator;

use BitBag\SyliusPrzelewy24Plugin\Subscription\Entity\SubscriptionInterface;

interface SubscriptionBasedRouterContextApplicatorInterface
{
    public function apply(SubscriptionInterface $subscription): void;
}
