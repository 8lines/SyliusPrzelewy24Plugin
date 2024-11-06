<?php

declare(strict_types=1);

namespace BitBag\SyliusPrzelewy24Plugin\Subscription\CommandDispatcher;

interface SavePrzelewy24CreditCardIfNotExistsDispatcherInterface
{
    public function dispatch(
        int $przelewy24CustomerId,
        string $cardMask,
        string $cardDate,
        string $cardRefId,
    ): void;
}
