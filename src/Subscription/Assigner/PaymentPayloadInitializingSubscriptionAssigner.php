<?php

declare(strict_types=1);

namespace BitBag\SyliusPrzelewy24Plugin\Subscription\Assigner;

use BitBag\SyliusPrzelewy24Plugin\Shared\Assigner\PaymentPayloadAssignerInterface;
use BitBag\SyliusPrzelewy24Plugin\Shared\Assigner\PaymentPayloadDataAssignerInterface;
use BitBag\SyliusPrzelewy24Plugin\Shared\Provider\PaymentPayloadProviderInterface;
use BitBag\SyliusPrzelewy24Plugin\Subscription\Checker\IsPaymentInitializingSubscriptionCheckerInterface;
use Sylius\Component\Payment\Model\PaymentRequestInterface;

final readonly class PaymentPayloadInitializingSubscriptionAssigner implements PaymentPayloadDataAssignerInterface
{
    public function __construct(
        private IsPaymentInitializingSubscriptionCheckerInterface $isPaymentInitializingSubscriptionChecker,
        private PaymentPayloadProviderInterface $paymentPayloadProvider,
        private PaymentPayloadAssignerInterface $paymentPayloadAssigner,
    ) {
    }

    public function assign(PaymentRequestInterface $paymentRequest): void
    {
        $initializingSubscription = $this->isPaymentInitializingSubscriptionChecker->isInitializingSubscription(
            paymentRequest: $paymentRequest,
        );

        $payload = $this->paymentPayloadProvider->provideFromPaymentRequest(
            paymentRequest: $paymentRequest,
        );

        $payload->withInitializingSubscription($initializingSubscription);

        $this->paymentPayloadAssigner->assign(
            paymentRequest: $paymentRequest,
            payload: $payload,
        );
    }
}
