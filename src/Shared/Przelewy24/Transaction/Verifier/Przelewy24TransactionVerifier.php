<?php

declare(strict_types=1);

namespace BitBag\SyliusPrzelewy24Plugin\Shared\Przelewy24\Transaction\Verifier;

use BitBag\SyliusPrzelewy24Plugin\Shared\Provider\PaymentApiClientProviderInterface;
use BitBag\SyliusPrzelewy24Plugin\Shared\Provider\PaymentPayloadProviderInterface;
use BitBag\SyliusPrzelewy24Plugin\Shared\Verifier\TransactionVerifierInterface;
use Przelewy24\Przelewy24;
use Sylius\Component\Payment\Model\PaymentRequestInterface;

final readonly class Przelewy24TransactionVerifier implements TransactionVerifierInterface
{
    public function __construct(
        private PaymentPayloadProviderInterface $paymentPayloadProvider,
        private PaymentApiClientProviderInterface $paymentApiClientProvider,
    ) {
    }

    public function verify(PaymentRequestInterface $paymentRequest): void
    {
        $payload = $this->paymentPayloadProvider->provideFromPaymentRequest(
            paymentRequest: $paymentRequest,
        );

        $payload->validateNotNull([
            'sessionId',
            'orderId',
            'amount',
            'currency',
        ]);

        /** @var Przelewy24 $przelewy24 */
        $przelewy24 = $this->paymentApiClientProvider->provideFromPaymentRequest(
            paymentRequest: $paymentRequest,
        );

        $przelewy24->transactions()->verify(
            sessionId: $payload->sessionId(),
            orderId: $payload->orderId(),
            amount: $payload->amount(),
            currency: $payload->currency(),
        );
    }
}
