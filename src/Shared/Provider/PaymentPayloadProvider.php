<?php

declare(strict_types=1);

namespace BitBag\SyliusPrzelewy24Plugin\Shared\Provider;

use BitBag\SyliusPrzelewy24Plugin\Shared\Payload\PaymentPayload;
use Sylius\Component\Core\Model\PaymentInterface;

final readonly class PaymentPayloadProvider implements PaymentPayloadProviderInterface
{
    public function provideFromPayment(PaymentInterface $payment): PaymentPayload
    {
        return PaymentPayload::fromArray(
            data: $payment->getDetails(),
        );
    }
}
