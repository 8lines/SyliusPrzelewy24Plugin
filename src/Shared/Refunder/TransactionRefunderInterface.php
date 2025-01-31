<?php

declare(strict_types=1);

namespace BitBag\SyliusPrzelewy24Plugin\Shared\Refunder;

interface TransactionRefunderInterface
{
    public function refund(RefundableRequestInterface $request): void;
}
