<?php

declare(strict_types=1);

namespace BitBag\SyliusPrzelewy24Plugin\Shared\Assigner;

use Sylius\Component\Payment\Model\PaymentRequestInterface;

interface PaymentResponseGatewayUrlAssignerInterface
{
    public function assign(
        PaymentRequestInterface $paymentRequest,
        string $gatewayUrl,
    ): void;
}
