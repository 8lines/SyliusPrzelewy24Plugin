<?php

declare(strict_types=1);

namespace BitBag\SyliusPrzelewy24Plugin\Shared\Verifier;

use Sylius\Component\Payment\Model\PaymentRequestInterface;

interface TransactionVerifierInterface
{
    public function verify(PaymentRequestInterface $paymentRequest): void;
}
