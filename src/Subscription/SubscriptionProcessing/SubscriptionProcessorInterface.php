<?php

declare(strict_types=1);

namespace BitBag\SyliusPrzelewy24Plugin\Subscription\SubscriptionProcessing;

use BitBag\SyliusPrzelewy24Plugin\Subscription\Entity\SubscriptionInterface;

interface SubscriptionProcessorInterface
{
    public function process(SubscriptionInterface $subscription): void;
}
