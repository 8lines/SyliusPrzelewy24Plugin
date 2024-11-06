<?php

declare(strict_types=1);

namespace BitBag\SyliusPrzelewy24Plugin\Subscription\Command;

final class RegisterNewSubscriptionCommand
{
    public function __construct(
        private readonly int $syliusRecurringOrderId,
    ) {
    }

    public function syliusRecurringOrderId(): int
    {
        return $this->syliusRecurringOrderId;
    }
}
