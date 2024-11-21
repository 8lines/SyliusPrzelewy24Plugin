<?php

declare(strict_types=1);

namespace BitBag\SyliusPrzelewy24Plugin\OneTime\OrderPay\Provider;

use Sylius\Bundle\PaymentBundle\Provider\HttpResponseProviderInterface;
use Sylius\Bundle\ResourceBundle\Controller\RequestConfiguration;
use Sylius\Component\Payment\Model\PaymentRequestInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Response;
use Webmozart\Assert\Assert;

final readonly class CaptureHttpResponseProvider implements HttpResponseProviderInterface
{
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
        /** @var string $gatewayUrl */
        $gatewayUrl = $paymentRequest->getResponseData()['gatewayUrl'] ?? null;

        Assert::notNull(
            value: $gatewayUrl,
            message: 'Gateway URL cannot be null.',
        );

        return new RedirectResponse($gatewayUrl);
    }
}
