<?php

declare(strict_types=1);

namespace BitBag\SyliusPrzelewy24Plugin\Shared\Provider;

use BitBag\SyliusPrzelewy24Plugin\Shared\Payload\PaymentPayload;
use Sylius\Component\Core\Model\PaymentInterface;
use Sylius\Component\Payment\Model\PaymentRequestInterface;

final readonly class PaymentPayloadProvider implements PaymentPayloadProviderInterface
{
    public function provideFromPaymentRequest(PaymentRequestInterface $paymentRequest): PaymentPayload
    {
        /** @var PaymentInterface $payment */
        $payment = $paymentRequest->getPayment();

        return $this->provideFromPayment($payment);
    }

    public function provideFromPayment(PaymentInterface $payment): PaymentPayload
    {
        return PaymentPayload::fromArray(
            data: $payment->getDetails(),
        );
    }
}
