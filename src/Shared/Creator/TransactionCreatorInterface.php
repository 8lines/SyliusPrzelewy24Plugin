<?php

declare(strict_types=1);

namespace BitBag\SyliusPrzelewy24Plugin\Shared\Creator;

interface TransactionCreatorInterface
{
    public function create(CreatableTransactionRequest $request): void;
}
