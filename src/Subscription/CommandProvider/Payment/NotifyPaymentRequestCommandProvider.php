<?php

declare(strict_types=1);

namespace BitBag\SyliusPrzelewy24Plugin\Subscription\CommandProvider\Payment;

use BitBag\SyliusPrzelewy24Plugin\Subscription\Command\Payment\NotifyPaymentRequest;
use Sylius\Bundle\PaymentBundle\CommandProvider\PaymentRequestCommandProviderInterface;
use Sylius\Component\Payment\Model\PaymentRequestInterface;

final readonly class NotifyPaymentRequestCommandProvider implements PaymentRequestCommandProviderInterface
{
    public function supports(PaymentRequestInterface $paymentRequest): bool
    {
        return PaymentRequestInterface::ACTION_NOTIFY === $paymentRequest->getAction();
    }

    public function provide(PaymentRequestInterface $paymentRequest): object
    {
        return new NotifyPaymentRequest(
            hash: $paymentRequest->getId(),
        );
    }
}
