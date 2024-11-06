<?php

declare(strict_types=1);

namespace BitBag\SyliusPrzelewy24Plugin\Subscription\Applicator;

use BitBag\SyliusPrzelewy24Plugin\Subscription\Entity\Przelewy24SubscriptionInterface;

interface Przelewy24SubscriptionBasedRouterContextApplicatorInterface
{
    public function apply(Przelewy24SubscriptionInterface $subscription): void;
}
