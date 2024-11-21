<?php

declare(strict_types=1);

namespace BitBag\SyliusPrzelewy24Plugin\Subscription\Checker;

use BitBag\SyliusPrzelewy24Plugin\Shared\Provider\PaymentPayloadProviderInterface;
use Sylius\Component\Payment\Model\PaymentRequestInterface;

final readonly class IsPayingWithExistingCardChecker implements IsPayingWithExistingCardCheckerInterface
{
    public function __construct(
        private PaymentPayloadProviderInterface $paymentPayloadProvider,
    ){
    }

    public function isPayingWithExistingCard(PaymentRequestInterface $paymentRequest): bool
    {
        $payload = $this->paymentPayloadProvider->provideFromPaymentRequest(
            paymentRequest: $paymentRequest,
        );

        return null !== $payload->cardRefId();
    }
}
