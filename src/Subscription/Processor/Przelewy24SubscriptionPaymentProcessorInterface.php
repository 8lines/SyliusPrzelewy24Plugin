<?php

declare(strict_types=1);

namespace BitBag\SyliusPrzelewy24Plugin\Subscription\Processor;

use BitBag\SyliusPrzelewy24Plugin\Subscription\Entity\Przelewy24SubscriptionInterface;

interface Przelewy24SubscriptionPaymentProcessorInterface
{
    public function processRecurringPayment(
        Przelewy24SubscriptionInterface $subscription,
        int $sequence,
    ): void;
}
