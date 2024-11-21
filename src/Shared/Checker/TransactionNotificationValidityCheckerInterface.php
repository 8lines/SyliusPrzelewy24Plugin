<?php

declare(strict_types=1);

namespace BitBag\SyliusPrzelewy24Plugin\Shared\Checker;

use Sylius\Component\Payment\Model\PaymentRequestInterface;

interface TransactionNotificationValidityCheckerInterface
{
    public function isValid(PaymentRequestInterface $paymentRequest): bool;
}
