<?php

declare(strict_types=1);

namespace BitBag\SyliusPrzelewy24Plugin\Shared\Assigner;

use BitBag\SyliusPrzelewy24Plugin\Shared\Payload\TransactionPayloadInterface;

final readonly class TransactionPayloadEmptyPayloadAssigner implements TransactionPayloadDataAssignerInterface
{
    /**
     * @param class-string<TransactionPayloadInterface> $transactionPayloadClassName
     */
    public function __construct(
        private string $transactionPayloadClassName,
    ) {
    }

    public function assign(PayloadAssignableRequestInterface $request): void
    {
        $payload = $this->transactionPayloadClassName::empty();

        $request->setTransactionPayload($payload);
    }
}
