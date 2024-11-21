<?php

declare(strict_types=1);

namespace BitBag\SyliusPrzelewy24Plugin\Shared\Assigner;

use Sylius\Component\Payment\Model\PaymentRequestInterface;

interface PaymentPayloadTransactionTokenAssignerInterface
{
    public function assign(
        PaymentRequestInterface $paymentRequest,
        string $transactionToken,
    ): void;
}
