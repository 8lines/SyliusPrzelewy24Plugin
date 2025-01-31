<?php

declare(strict_types=1);

namespace BitBag\SyliusPrzelewy24Plugin\Shared\Processor;

use BitBag\SyliusPrzelewy24Plugin\Shared\Verifier\TransactionVerifierInterface;

final readonly class TransactionNotificationVerifierProcessor implements TransactionNotificationProcessorInterface
{
    public function __construct(
        private TransactionVerifierInterface $transactionVerifier,
    ) {
    }

    public function process(NotificationRequestInterface $request): void
    {
        $this->transactionVerifier->verify(
            request: $request,
        );
    }
}
