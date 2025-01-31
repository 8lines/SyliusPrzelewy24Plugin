<?php

declare(strict_types=1);

namespace BitBag\SyliusPrzelewy24Plugin\Subscription\OrderPay\Provider;

use BitBag\SyliusPrzelewy24Plugin\Shared\Entity\TransactionalPaymentRequestInterface;
use BitBag\SyliusPrzelewy24Plugin\Shared\Payload\PaymentPayload;
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
        /** @var TransactionalPaymentRequestInterface $paymentRequest */

        Assert::isInstanceOf(
            value: $paymentRequest,
            class: TransactionalPaymentRequestInterface::class,
            message: 'Payment request must be an instance of %2$s, but it is %s.',
        );

        /** @var PaymentPayload $payload */
        $payload = $paymentRequest->getTransactionPayload();

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
