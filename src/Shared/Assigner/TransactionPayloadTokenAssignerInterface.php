<?php

declare(strict_types=1);

namespace BitBag\SyliusPrzelewy24Plugin\Shared\Assigner;

interface TransactionPayloadTokenAssignerInterface
{
    public function assign(
        PayloadAssignableRequestInterface $request,
        string $transactionToken,
    ): void;
}
