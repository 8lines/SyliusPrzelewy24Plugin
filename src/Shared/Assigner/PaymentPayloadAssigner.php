<?php

declare(strict_types=1);

namespace BitBag\SyliusPrzelewy24Plugin\Shared\Assigner;

use BitBag\SyliusPrzelewy24Plugin\Shared\Payload\PaymentPayload;
use Sylius\Component\Core\Model\PaymentInterface;
use Sylius\Component\Payment\Model\PaymentRequestInterface;

final readonly class PaymentPayloadAssigner implements PaymentPayloadAssignerInterface
{
    public function assign(
        PaymentRequestInterface $paymentRequest,
        PaymentPayload $payload,
    ): void {
        /** @var PaymentInterface $payment */
        $payment = $paymentRequest->getPayment();

        $payment->setDetails(
            details: $payload->toArray(),
        );
    }
}
