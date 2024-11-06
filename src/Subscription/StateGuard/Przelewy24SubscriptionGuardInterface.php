<?php

declare(strict_types=1);

namespace BitBag\SyliusPrzelewy24Plugin\Subscription\StateGuard;

use BitBag\SyliusPrzelewy24Plugin\Subscription\Entity\Przelewy24SubscriptionInterface;

interface Przelewy24SubscriptionGuardInterface
{
    public function isEligible(Przelewy24SubscriptionInterface $subscription): bool;
}
