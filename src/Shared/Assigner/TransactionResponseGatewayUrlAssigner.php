<?php

declare(strict_types=1);

namespace BitBag\SyliusPrzelewy24Plugin\Shared\Assigner;

final readonly class TransactionResponseGatewayUrlAssigner implements TransactionResponseGatewayUrlAssignerInterface
{
    public function assign(
        ResponseAssignableTransactionRequestInterface $request,
        string $gatewayUrl,
    ): void {
        $request->setTransactionResponse([
            'gatewayUrl' => $gatewayUrl,
        ]);
    }
}
