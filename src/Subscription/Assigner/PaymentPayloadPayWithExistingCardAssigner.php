<?php

declare(strict_types=1);

namespace BitBag\SyliusPrzelewy24Plugin\Subscription\Assigner;

use BitBag\SyliusPrzelewy24Plugin\Shared\Assigner\PaymentPayloadAssignerInterface;
use BitBag\SyliusPrzelewy24Plugin\Shared\Assigner\PaymentPayloadDataAssignerInterface;
use BitBag\SyliusPrzelewy24Plugin\Shared\Provider\PaymentPayloadProviderInterface;
use BitBag\SyliusPrzelewy24Plugin\Subscription\Checker\IsPayingWithExistingCardCheckerInterface;
use Sylius\Component\Payment\Model\PaymentRequestInterface;

final readonly class PaymentPayloadPayWithExistingCardAssigner implements PaymentPayloadDataAssignerInterface
{
    public function __construct(
        private IsPayingWithExistingCardCheckerInterface $isPayingWithExistingCardChecker,
        private PaymentPayloadProviderInterface $paymentPayloadProvider,
        private PaymentPayloadAssignerInterface $paymentPayloadAssigner,
    ) {
    }

    public function assign(PaymentRequestInterface $paymentRequest): void
    {
        $payingWithExistingCard = $this->isPayingWithExistingCardChecker->isPayingWithExistingCard(
            paymentRequest: $paymentRequest,
        );

        $payload = $this->paymentPayloadProvider->provideFromPaymentRequest(
            paymentRequest: $paymentRequest,
        );

        $payload->withPayWithExistingCard($payingWithExistingCard);

        $this->paymentPayloadAssigner->assign(
            paymentRequest: $paymentRequest,
            payload: $payload,
        );
    }
}
