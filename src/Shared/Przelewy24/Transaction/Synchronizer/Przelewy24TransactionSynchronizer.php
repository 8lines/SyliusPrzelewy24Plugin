<?php

declare(strict_types=1);

namespace BitBag\SyliusPrzelewy24Plugin\Shared\Przelewy24\Transaction\Synchronizer;

use BitBag\SyliusPrzelewy24Plugin\Shared\Provider\PaymentApiClientProviderInterface;
use BitBag\SyliusPrzelewy24Plugin\Shared\Synchronizer\SynchronizableRequestInterface;
use BitBag\SyliusPrzelewy24Plugin\Shared\Synchronizer\TransactionSynchronizerInterface;
use Przelewy24\Przelewy24;

final readonly class Przelewy24TransactionSynchronizer implements TransactionSynchronizerInterface
{
    /**
     * @param PaymentApiClientProviderInterface<Przelewy24> $paymentApiClientProvider
     */
    public function __construct(
        private PaymentApiClientProviderInterface $paymentApiClientProvider,
    ) {
    }

    public function synchronize(SynchronizableRequestInterface $request): void
    {
        $payload = $request->getTransactionPayload();
        $payload->validateNotNull(['sessionId']);

        $przelewy24 = $this->paymentApiClientProvider->provideFromPaymentMethod(
            paymentMethod: $request->getPaymentMethod(),
        );

        $transaction = $przelewy24->transactions()->find(
            sessionId: $payload->sessionId(),
        );

        $payload->withOrderId($transaction->orderId());
        $payload->withAmount($transaction->amount());
        $payload->withCurrency($transaction->currency());
        $payload->withStatement(\lcfirst($transaction->statement()));
        $payload->withMethodId($transaction->paymentMethod());

        $request->setTransactionPayload($payload);
    }
}
