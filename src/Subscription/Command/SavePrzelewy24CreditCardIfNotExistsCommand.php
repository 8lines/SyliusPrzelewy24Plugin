<?php

declare(strict_types=1);

namespace BitBag\SyliusPrzelewy24Plugin\Subscription\Command;

final class SavePrzelewy24CreditCardIfNotExistsCommand
{
    public function __construct(
        private readonly int $przelewy24CustomerId,
        private readonly string $cardMask,
        private readonly string $cardDate,
        private readonly string $cardRefId,
    ) {
    }

    public function przelewy24CustomerId(): int
    {
        return $this->przelewy24CustomerId;
    }

    public function cardMask(): string
    {
        return $this->cardMask;
    }

    public function cardDate(): string
    {
        return $this->cardDate;
    }

    public function cardRefId(): string
    {
        return $this->cardRefId;
    }
}
