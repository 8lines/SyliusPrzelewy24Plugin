<?php

declare(strict_types=1);

namespace BitBag\SyliusPrzelewy24Plugin\Shared\Przelewy24\Transaction\Verifier;

use BitBag\SyliusPrzelewy24Plugin\Shared\Provider\PaymentApiClientProviderInterface;
use BitBag\SyliusPrzelewy24Plugin\Shared\Verifier\TransactionVerifierInterface;
use BitBag\SyliusPrzelewy24Plugin\Shared\Verifier\VerifiableRequestInterface;
use Przelewy24\Przelewy24;

final readonly class Przelewy24TransactionVerifier implements TransactionVerifierInterface
{
    /**
     * @param PaymentApiClientProviderInterface<Przelewy24> $paymentApiClientProvider
     */
    public function __construct(
        private PaymentApiClientProviderInterface $paymentApiClientProvider,
    ) {
    }

    public function verify(VerifiableRequestInterface $request): void
    {
        $payload = $request->getTransactionPayload();
        $payload->validateNotNull([
            'sessionId',
            'orderId',
            'amount',
            'currency',
        ]);

        $przelewy24 = $this->paymentApiClientProvider->provideFromPaymentMethod(
            paymentMethod: $request->getPaymentMethod(),
        );

        $przelewy24->transactions()->verify(
            sessionId: $payload->sessionId(),
            orderId: $payload->orderId(),
            amount: $payload->amount(),
            currency: $payload->currency(),
        );
    }
}
