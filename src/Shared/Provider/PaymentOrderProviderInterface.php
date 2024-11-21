<?php

declare(strict_types=1);

namespace BitBag\SyliusPrzelewy24Plugin\Shared\Provider;

use Sylius\Component\Core\Model\OrderInterface;
use Sylius\Component\Payment\Model\PaymentRequestInterface;

interface PaymentOrderProviderInterface
{
    public function provide(PaymentRequestInterface $paymentRequest): OrderInterface;
}
