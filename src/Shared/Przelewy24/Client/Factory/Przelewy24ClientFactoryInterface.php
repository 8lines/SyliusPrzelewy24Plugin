<?php

declare(strict_types=1);

namespace BitBag\SyliusPrzelewy24Plugin\Shared\Przelewy24\Client\Factory;

use Przelewy24\Przelewy24;
use Sylius\Component\Core\Model\PaymentMethodInterface;

interface Przelewy24ClientFactoryInterface
{
    public function createFromPaymentMethod(PaymentMethodInterface $paymentMethod): Przelewy24;
}
