<?php

declare(strict_types=1);

namespace BitBag\SyliusPrzelewy24Plugin\Subscription\StateProcessor;

use Sylius\Component\Core\Model\PaymentInterface;

interface SyliusPaymentStateProcessorInterface
{
    public function process(PaymentInterface $payment): void;
}
