<?php

declare(strict_types=1);

namespace BitBag\SyliusPrzelewy24Plugin\Shared\Synchronizer;

use Sylius\Component\Payment\Model\PaymentRequestInterface;

interface TransactionSynchronizerInterface
{
    public function synchronize(PaymentRequestInterface $paymentRequest): void;
}
