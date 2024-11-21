<?php

declare(strict_types=1);

namespace BitBag\SyliusPrzelewy24Plugin\Shared\Processor;

use Sylius\Component\Payment\Model\PaymentRequestInterface;

interface TransactionNotificationProcessorInterface
{
    public function process(PaymentRequestInterface $paymentRequest): void;
}
