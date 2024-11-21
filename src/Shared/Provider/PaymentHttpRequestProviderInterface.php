<?php

declare(strict_types=1);

namespace BitBag\SyliusPrzelewy24Plugin\Shared\Provider;

use Sylius\Component\Payment\Model\PaymentRequestInterface;
use Symfony\Component\HttpFoundation\Request;

interface PaymentHttpRequestProviderInterface
{
    public function provide(PaymentRequestInterface $paymentRequest): ?Request;
}
