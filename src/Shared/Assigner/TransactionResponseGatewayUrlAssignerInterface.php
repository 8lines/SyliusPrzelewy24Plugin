<?php

declare(strict_types=1);

namespace BitBag\SyliusPrzelewy24Plugin\Shared\Assigner;

interface TransactionResponseGatewayUrlAssignerInterface
{
    public function assign(
        ResponseAssignableTransactionRequestInterface $request,
        string $gatewayUrl,
    ): void;
}
