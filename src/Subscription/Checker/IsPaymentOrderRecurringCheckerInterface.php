<?php

declare(strict_types=1);

namespace BitBag\SyliusPrzelewy24Plugin\Subscription\Checker;

use Sylius\Component\Payment\Model\PaymentRequestInterface;

interface IsPaymentOrderRecurringCheckerInterface
{
    public function isRecurring(PaymentRequestInterface $paymentRequest): bool;
}
