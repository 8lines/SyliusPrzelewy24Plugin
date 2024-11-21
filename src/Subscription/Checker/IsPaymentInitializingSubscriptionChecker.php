<?php

declare(strict_types=1);

namespace BitBag\SyliusPrzelewy24Plugin\Subscription\Checker;

use BitBag\SyliusPrzelewy24Plugin\Shared\Provider\PaymentOrderProviderInterface;
use BitBag\SyliusPrzelewy24Plugin\Subscription\Entity\RecurringSyliusOrderInterface;
use BitBag\SyliusPrzelewy24Plugin\Subscription\Entity\Subscription;
use Sylius\Component\Payment\Model\PaymentRequestInterface;

final readonly class IsPaymentInitializingSubscriptionChecker implements IsPaymentInitializingSubscriptionCheckerInterface
{
    public function __construct(
        private PaymentOrderProviderInterface $paymentOrderProvider,
    ) {
    }

    public function isInitializingSubscription(PaymentRequestInterface $paymentRequest): bool
    {
        /** @var RecurringSyliusOrderInterface $order */
        $order = $this->paymentOrderProvider->provide(
            paymentRequest: $paymentRequest,
        );

        return Subscription::INITIAL_SEQUENCE === $order->getRecurringPrzelewy24Order()->getRecurringSequenceIndex();
    }
}
