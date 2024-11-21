<?php

declare(strict_types=1);

namespace BitBag\SyliusPrzelewy24Plugin\Subscription\Assigner;

use BitBag\SyliusPrzelewy24Plugin\Shared\Assigner\PaymentPayloadAssignerInterface;
use BitBag\SyliusPrzelewy24Plugin\Shared\Assigner\PaymentPayloadDataAssignerInterface;
use BitBag\SyliusPrzelewy24Plugin\Shared\Provider\PaymentPayloadProviderInterface;
use BitBag\SyliusPrzelewy24Plugin\Subscription\Checker\IsPaymentOrderRecurringCheckerInterface;
use Sylius\Component\Payment\Model\PaymentRequestInterface;

final readonly class PaymentPayloadRecurringAssigner implements PaymentPayloadDataAssignerInterface
{
    public function __construct(
        private IsPaymentOrderRecurringCheckerInterface $isPaymentOrderRecurringChecker,
        private PaymentPayloadProviderInterface $paymentPayloadProvider,
        private PaymentPayloadAssignerInterface $paymentPayloadAssigner,
    ) {
    }

    public function assign(PaymentRequestInterface $paymentRequest): void
    {
        $recurring = $this->isPaymentOrderRecurringChecker->isRecurring(
            paymentRequest: $paymentRequest,
        );

        $payload = $this->paymentPayloadProvider->provideFromPaymentRequest(
            paymentRequest: $paymentRequest,
        );

        $payload->withRecurring($recurring);

        $this->paymentPayloadAssigner->assign(
            paymentRequest: $paymentRequest,
            payload: $payload,
        );
    }
}
