<?php

declare(strict_types=1);

namespace BitBag\SyliusPrzelewy24Plugin\OneTime\CommandProvider\Payment;

use BitBag\SyliusPrzelewy24Plugin\OneTime\Command\Payment\SyncPaymentRequest;
use Sylius\Bundle\PaymentBundle\CommandProvider\PaymentRequestCommandProviderInterface;
use Sylius\Component\Payment\Model\PaymentRequestInterface;

final readonly class SyncPaymentRequestCommandProvider implements PaymentRequestCommandProviderInterface
{
    public function supports(PaymentRequestInterface $paymentRequest): bool
    {
        return PaymentRequestInterface::ACTION_SYNC === $paymentRequest->getAction();
    }

    public function provide(PaymentRequestInterface $paymentRequest): object
    {
        return new SyncPaymentRequest(
            hash: $paymentRequest->getId(),
        );
    }
}
