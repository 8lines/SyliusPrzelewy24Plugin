<?php

declare(strict_types=1);

namespace BitBag\SyliusPrzelewy24Plugin\Subscription\Checker;

use Sylius\Component\Core\Model\PaymentInterface;

interface IsPrzelewy24SubscriptionPaymentFailedCheckerInterface
{
    public function isFailed(PaymentInterface $payment): bool;
}
