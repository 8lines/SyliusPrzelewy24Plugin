<?php

declare(strict_types=1);

namespace BitBag\SyliusPrzelewy24Plugin\Subscription\SubscriptionProcessing;

interface ActiveSubscriptionsProcessorInterface
{
    public function processActiveSubscriptions(): void;
}
