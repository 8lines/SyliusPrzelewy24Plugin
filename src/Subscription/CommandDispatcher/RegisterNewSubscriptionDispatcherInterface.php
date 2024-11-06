<?php

declare(strict_types=1);

namespace BitBag\SyliusPrzelewy24Plugin\Subscription\CommandDispatcher;

interface RegisterNewSubscriptionDispatcherInterface
{
    public function dispatch(int $syliusRecurringOrderId): void;
}
