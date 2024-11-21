<?php

declare(strict_types=1);

namespace BitBag\SyliusPrzelewy24Plugin\Subscription\Checker;

use Sylius\Component\Payment\Model\PaymentRequestInterface;

interface IsPaymentInitializingSubscriptionCheckerInterface
{
    public function isInitializingSubscription(PaymentRequestInterface $paymentRequest): bool;
}
