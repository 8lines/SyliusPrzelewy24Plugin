<?php

declare(strict_types=1);

namespace BitBag\SyliusPrzelewy24Plugin\Subscription\OrderPay\Provider;

use BitBag\SyliusPrzelewy24Plugin\Shared\Provider\PaymentPayloadProviderInterface;
use Sylius\Bundle\PaymentBundle\Provider\HttpResponseProviderInterface;
use Sylius\Bundle\ResourceBundle\Controller\RequestConfiguration;
use Sylius\Component\Payment\Model\PaymentRequestInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Response;
use Webmozart\Assert\Assert;

final readonly class CaptureHttpResponseProvider implements HttpResponseProviderInterface
{
    public function __construct(
        private PaymentPayloadProviderInterface $paymentPayloadProvider,
    ) {
    }

    public function supports(
        RequestConfiguration $requestConfiguration,
        PaymentRequestInterface $paymentRequest,
    ): bool {
        return PaymentRequestInterface::ACTION_CAPTURE === $paymentRequest->getAction();
    }

    public function getResponse(
        RequestConfiguration $requestConfiguration,
        PaymentRequestInterface $paymentRequest,
    ): Response {
        $payload = $this->paymentPayloadProvider->provideFromPaymentRequest(
            paymentRequest: $paymentRequest,
        );

        $payload->validateNotNull([
            'initializingSubscription',
            'payWithExistingCard',
        ]);

        if (false === $payload->initializingSubscription() || true === $payload->payWithExistingCard()) {
            $payload->validateNotNull(['afterUrl']);

            return new RedirectResponse($payload->afterUrl());
        }

        /** @var string $gatewayUrl */
        $gatewayUrl = $paymentRequest->getResponseData()['gatewayUrl'] ?? null;

        Assert::notNull(
            value: $gatewayUrl,
            message: 'Gateway URL cannot be null.',
        );

        return new RedirectResponse($gatewayUrl);
    }
}
