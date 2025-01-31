<?php

declare(strict_types=1);

namespace BitBag\SyliusPrzelewy24Plugin\Shared\Assigner;

use BitBag\SyliusPrzelewy24Plugin\Shared\Resolver\TransactionNotifyUrlResolverInterface;

final readonly class TransactionPayloadNotifyUrlAssigner implements TransactionPayloadDataAssignerInterface
{
    public function __construct(
        private TransactionNotifyUrlResolverInterface $transactionNotifyUrlResolver,
    ) {
    }

    public function assign(PayloadAssignableRequestInterface $request): void
    {
        $notifyUrl = $this->transactionNotifyUrlResolver->resolve(
            request: $request,
        );

        $payload = $request->getTransactionPayload();
        $payload->withNotifyUrl($notifyUrl);

        $request->setTransactionPayload($payload);
    }
}
