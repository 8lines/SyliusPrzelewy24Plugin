<?php

declare(strict_types=1);

namespace BitBag\SyliusPrzelewy24Plugin\Shared\Charger;

use Sylius\Component\Payment\Model\PaymentRequestInterface;

interface CardChargerInterface
{
    public function charge(PaymentRequestInterface $paymentRequest): void;
}
