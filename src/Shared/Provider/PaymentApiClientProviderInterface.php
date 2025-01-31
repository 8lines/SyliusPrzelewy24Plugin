<?php

declare(strict_types=1);

namespace BitBag\SyliusPrzelewy24Plugin\Shared\Provider;

use Sylius\Component\Core\Model\PaymentMethodInterface;

/**
 * @template T
 */
interface PaymentApiClientProviderInterface
{
    /**
     * @return T
     */
    public function provideFromPaymentMethod(PaymentMethodInterface $paymentMethod);
}
