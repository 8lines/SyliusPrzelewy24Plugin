<?php

declare(strict_types=1);

namespace BitBag\SyliusPrzelewy24Plugin\Subscription\Checker;

use Sylius\Component\Payment\Model\PaymentRequestInterface;

interface IsPayingWithExistingCardCheckerInterface
{
    public function isPayingWithExistingCard(PaymentRequestInterface $paymentRequest): bool;
}
