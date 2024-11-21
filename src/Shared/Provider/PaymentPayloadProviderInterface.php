<?php

declare(strict_types=1);

namespace BitBag\SyliusPrzelewy24Plugin\Shared\Provider;

use BitBag\SyliusPrzelewy24Plugin\Shared\Payload\PaymentPayload;
use Sylius\Component\Core\Model\PaymentInterface;
use Sylius\Component\Payment\Model\PaymentRequestInterface;

interface PaymentPayloadProviderInterface
{
    public function provideFromPaymentRequest(PaymentRequestInterface $paymentRequest): PaymentPayload;

    public function provideFromPayment(PaymentInterface $payment): PaymentPayload;
}
