<?php

declare(strict_types=1);

namespace BitBag\SyliusPrzelewy24Plugin\Shared\Assigner;

final readonly class TransactionPayloadTokenAssigner implements TransactionPayloadTokenAssignerInterface
{
    public function assign(
        PayloadAssignableRequestInterface $request,
        string $transactionToken,
    ): void {
        $payload = $request->getTransactionPayload();
        $payload->withTransactionToken($transactionToken);

        $request->setTransactionPayload($payload);
    }
}
