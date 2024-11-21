<?php

declare(strict_types=1);

namespace BitBag\SyliusPrzelewy24Plugin\Shared\StateResolver;

use Sylius\Component\Payment\Model\PaymentRequestInterface;

interface PaymentStateResolverInterface
{
    public function resolve(PaymentRequestInterface $paymentRequest): void;
}
