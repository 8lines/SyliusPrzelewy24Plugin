<?php

declare(strict_types=1);

namespace BitBag\SyliusPrzelewy24Plugin\Subscription\Command;

final class MarkOrderRecurringCommand
{
    public function __construct(
        private readonly int $przelewy24OrderId,
    ) {
    }

    public function przelewy24OrderId(): int
    {
        return $this->przelewy24OrderId;
    }
}
