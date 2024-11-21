<?php

declare(strict_types=1);

namespace BitBag\SyliusPrzelewy24Plugin\Subscription\Checker;

use BitBag\SyliusPrzelewy24Plugin\Shared\Provider\PaymentOrderProviderInterface;
use BitBag\SyliusPrzelewy24Plugin\Subscription\Entity\RecurringSyliusOrderInterface;
use Sylius\Component\Payment\Model\PaymentRequestInterface;

final readonly class IsPaymentOrderRecurringChecker implements IsPaymentOrderRecurringCheckerInterface
{
    public function __construct(
        private PaymentOrderProviderInterface $paymentOrderProvider,
    ) {
    }

    public function isRecurring(PaymentRequestInterface $paymentRequest): bool
    {
        /** @var RecurringSyliusOrderInterface $order */
        $order = $this->paymentOrderProvider->provide(
            paymentRequest: $paymentRequest,
        );

        return true === $order->getRecurringPrzelewy24Order()->isRecurring();
    }
}
