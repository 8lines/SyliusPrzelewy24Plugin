<?php

declare(strict_types=1);

namespace BitBag\SyliusPrzelewy24Plugin\Subscription\Processor;

interface Przelewy24SubscriptionAwaitingPaymentsProcessorInterface
{
    public function process(): void;
}
