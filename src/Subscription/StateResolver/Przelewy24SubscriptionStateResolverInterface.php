<?php

declare(strict_types=1);

namespace BitBag\SyliusPrzelewy24Plugin\Subscription\StateResolver;

use BitBag\SyliusPrzelewy24Plugin\Subscription\Entity\Przelewy24SubscriptionInterface;

interface Przelewy24SubscriptionStateResolverInterface
{
    public function resolve(Przelewy24SubscriptionInterface $subscription): void;
}
