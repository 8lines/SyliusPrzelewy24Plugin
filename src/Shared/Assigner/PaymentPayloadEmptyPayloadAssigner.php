<?php

declare(strict_types=1);

namespace BitBag\SyliusPrzelewy24Plugin\Shared\Assigner;

use BitBag\SyliusPrzelewy24Plugin\Shared\Payload\PaymentPayload;
use Sylius\Component\Payment\Model\PaymentRequestInterface;

final readonly class PaymentPayloadEmptyPayloadAssigner implements PaymentPayloadDataAssignerInterface
{
    public function __construct(
        private PaymentPayloadAssignerInterface $paymentPayloadAssigner,
    ) {
    }

    public function assign(PaymentRequestInterface $paymentRequest): void
    {
        $payload = PaymentPayload::empty();

        $this->paymentPayloadAssigner->assign(
            paymentRequest: $paymentRequest,
            payload: $payload,
        );
    }
}
