<?php

declare(strict_types=1);

namespace BitBag\SyliusPrzelewy24Plugin\Shared\Assigner;

use Sylius\Component\Payment\Model\PaymentRequestInterface;

final readonly class PaymentResponseGatewayUrlAssigner implements PaymentResponseGatewayUrlAssignerInterface
{
    public function assign(
        PaymentRequestInterface $paymentRequest,
        string $gatewayUrl,
    ): void {
        $paymentRequest->setResponseData([
            'gatewayUrl' => $gatewayUrl,
        ]);
    }
}
