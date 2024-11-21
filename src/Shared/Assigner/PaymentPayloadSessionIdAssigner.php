<?php

declare(strict_types=1);

namespace BitBag\SyliusPrzelewy24Plugin\Shared\Assigner;

use BitBag\SyliusPrzelewy24Plugin\Shared\Provider\PaymentPayloadProviderInterface;
use Sylius\Component\Payment\Model\PaymentRequestInterface;
use Sylius\Resource\Generator\RandomnessGeneratorInterface;

final readonly class PaymentPayloadSessionIdAssigner implements PaymentPayloadDataAssignerInterface
{
    public const SESSION_ID_LENGTH = 32;

    public function __construct(
        private RandomnessGeneratorInterface $randomnessGenerator,
        private PaymentPayloadProviderInterface $paymentPayloadProvider,
        private PaymentPayloadAssignerInterface $paymentPayloadAssigner,
    ) {
    }

    public function assign(PaymentRequestInterface $paymentRequest): void
    {
        $sessionId = $this->randomnessGenerator->generateUriSafeString(
            length: self::SESSION_ID_LENGTH,
        );

        $payload = $this->paymentPayloadProvider->provideFromPaymentRequest(
            paymentRequest: $paymentRequest,
        );

        $payload->withSessionId($sessionId);

        $this->paymentPayloadAssigner->assign(
            paymentRequest: $paymentRequest,
            payload: $payload,
        );
    }
}
