<?php

declare(strict_types=1);

namespace BitBag\SyliusPrzelewy24Plugin\Shared\Resolver;

use Sylius\Component\Core\Model\PaymentInterface;
use Sylius\Component\Core\Model\PaymentMethodInterface;
use Sylius\Component\Payment\Model\PaymentRequestInterface;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Webmozart\Assert\Assert;

final readonly class PaymentNotifyUrlResolver implements PaymentNotifyUrlResolverInterface
{
    public function __construct(
        private UrlGeneratorInterface $urlGenerator,
    ) {
    }

    public function resolve(PaymentRequestInterface $paymentRequest): string
    {
        /** @var string $paymentRequestHash */
        $paymentRequestHash = $paymentRequest->getHash()?->toRfc4122();

        Assert::notNull(
            value: $paymentRequestHash,
            message: 'Payment request hash cannot be null.',
        );

        /** @var PaymentInterface $payment */
        $payment = $paymentRequest->getPayment();

        /** @var PaymentMethodInterface $paymentMethod */
        $paymentMethod = $payment->getMethod();

        Assert::notNull(
            value: $paymentMethod,
            message: 'Payment method cannot be null.',
        );

        /** @var string $paymentMethodCode */
        $paymentMethodCode = $paymentMethod->getCode();

        Assert::notNull(
            value: $paymentMethodCode,
            message: 'Payment method code cannot be null.',
        );

        return $this->urlGenerator->generate(
            name: 'sylius_payment_method_notify',
            parameters: [
                'code' => $paymentMethodCode,
                'hash' => $paymentRequestHash,
            ],
            referenceType: UrlGeneratorInterface::ABSOLUTE_URL,
        );
    }
}
