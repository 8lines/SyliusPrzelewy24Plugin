<?php

declare(strict_types=1);

namespace BitBag\SyliusPrzelewy24Plugin\Shared\Assigner;

use BitBag\SyliusPrzelewy24Plugin\Shared\Resolver\TransactionAfterUrlResolverInterface;

final readonly class TransactionPayloadAfterUrlAssigner implements TransactionPayloadDataAssignerInterface
{
    public function __construct(
        private TransactionAfterUrlResolverInterface $transactionAfterUrlResolver,
    ) {
    }

    public function assign(PayloadAssignableRequestInterface $request): void
    {
        $afterUrl = $this->transactionAfterUrlResolver->resolve(
            request: $request,
        );

        $payload = $request->getTransactionPayload();
        $payload->withAfterUrl($afterUrl);

        $request->setTransactionPayload($payload);
    }
}
