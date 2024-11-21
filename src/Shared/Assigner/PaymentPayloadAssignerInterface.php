<?php

declare(strict_types=1);

namespace BitBag\SyliusPrzelewy24Plugin\Shared\Assigner;

use BitBag\SyliusPrzelewy24Plugin\Shared\Payload\PaymentPayload;
use Sylius\Component\Payment\Model\PaymentRequestInterface;

interface PaymentPayloadAssignerInterface
{
    public function assign(
        PaymentRequestInterface $paymentRequest,
        PaymentPayload $payload,
    ): void;
}
