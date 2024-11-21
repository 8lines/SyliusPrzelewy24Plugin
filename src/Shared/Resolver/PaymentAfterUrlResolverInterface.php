<?php

declare(strict_types=1);

namespace BitBag\SyliusPrzelewy24Plugin\Shared\Resolver;

use Sylius\Component\Payment\Model\PaymentRequestInterface;

interface PaymentAfterUrlResolverInterface
{
    public function resolve(PaymentRequestInterface $paymentRequest): string;
}
