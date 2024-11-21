<?php

declare(strict_types=1);

namespace BitBag\SyliusPrzelewy24Plugin\Shared\Provider;

use Sylius\Component\Core\Model\PaymentInterface;
use Sylius\Component\Payment\Model\PaymentRequestInterface;

/**
 * @template T
 */
interface PaymentApiClientProviderInterface
{
    /**
     * @return T
     */
    public function provideFromPaymentRequest(PaymentRequestInterface $paymentRequest): mixed;

    /**
     * @return T
     */
    public function provideFromPayment(PaymentInterface $payment): mixed;
}
