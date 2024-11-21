<?php

declare(strict_types=1);

namespace BitBag\SyliusPrzelewy24Plugin\Shared\Assigner;

use BitBag\SyliusPrzelewy24Plugin\Shared\Provider\PaymentPayloadProviderInterface;
use Sylius\Component\Payment\Model\PaymentRequestInterface;

final readonly class PaymentPayloadTransactionTokenAssigner implements PaymentPayloadTransactionTokenAssignerInterface
{
    public function __construct(
        private PaymentPayloadProviderInterface $paymentPayloadProvider,
        private PaymentPayloadAssignerInterface $paymentPayloadAssigner,
    ) {
    }

    public function assign(
        PaymentRequestInterface $paymentRequest,
        string $transactionToken,
    ): void {
        $payload = $this->paymentPayloadProvider->provideFromPaymentRequest(
            paymentRequest:  $paymentRequest,
        );

        $payload->withTransactionToken($transactionToken);

        $this->paymentPayloadAssigner->assign(
            paymentRequest: $paymentRequest,
            payload: $payload,
        );
    }
}
