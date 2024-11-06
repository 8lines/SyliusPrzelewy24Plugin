<?php

declare(strict_types=1);

namespace BitBag\SyliusPrzelewy24Plugin\Subscription\Applicator;

use Sylius\Component\Core\Model\PaymentInterface;

interface PaymentFailedStateApplicatorInterface
{
    public function apply(PaymentInterface $payment): void;
}
