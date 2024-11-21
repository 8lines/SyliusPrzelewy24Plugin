<?php

declare(strict_types=1);

namespace BitBag\SyliusPrzelewy24Plugin\Shared\Przelewy24\Client\Provider;

use BitBag\SyliusPrzelewy24Plugin\Shared\Provider\PaymentApiClientProviderInterface;
use BitBag\SyliusPrzelewy24Plugin\Shared\Przelewy24\Client\Factory\Przelewy24ClientFactoryInterface;
use Przelewy24\Przelewy24;
use Sylius\Component\Core\Model\PaymentInterface;
use Sylius\Component\Core\Model\PaymentMethodInterface;
use Sylius\Component\Payment\Model\PaymentRequestInterface;
use Webmozart\Assert\Assert;

/**
 * @implements PaymentApiClientProviderInterface<Przelewy24>
 */
final readonly class Przelewy24ApiClientProvider implements PaymentApiClientProviderInterface
{
    public function __construct(
        private Przelewy24ClientFactoryInterface $przelewy24ClientFactory,
    ) {
    }

    public function provideFromPaymentRequest(PaymentRequestInterface $paymentRequest): Przelewy24
    {
        /** @var PaymentInterface $payment */
        $payment = $paymentRequest->getPayment();

        return $this->provideFromPayment($payment);
    }

    public function provideFromPayment(PaymentInterface $payment): Przelewy24
    {
        /** @var PaymentMethodInterface $paymentMethod */
        $paymentMethod = $payment->getMethod();

        Assert::notNull(
            value: $paymentMethod,
            message: 'Payment method cannot be null.',
        );

        return $this->przelewy24ClientFactory->createFromPaymentMethod(
            paymentMethod: $paymentMethod,
        );
    }
}
