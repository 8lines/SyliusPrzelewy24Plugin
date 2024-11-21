<?php

declare(strict_types=1);

namespace BitBag\SyliusPrzelewy24Plugin\Shared\Przelewy24\Notification\Checker;

use BitBag\SyliusPrzelewy24Plugin\Shared\Checker\TransactionNotificationValidityCheckerInterface;
use BitBag\SyliusPrzelewy24Plugin\Shared\Provider\PaymentApiClientProviderInterface;
use BitBag\SyliusPrzelewy24Plugin\Shared\Provider\PaymentHttpRequestProviderInterface;
use BitBag\SyliusPrzelewy24Plugin\Shared\Provider\PaymentOrderProviderInterface;
use BitBag\SyliusPrzelewy24Plugin\Shared\Provider\PaymentPayloadProviderInterface;
use Przelewy24\Przelewy24;
use Sylius\Component\Payment\Model\PaymentRequestInterface;
use Symfony\Component\HttpFoundation\Request;
use Webmozart\Assert\Assert;

final readonly class Przelewy24TransactionNotificationSignValidityChecker implements TransactionNotificationValidityCheckerInterface
{
    public function __construct(
        private PaymentPayloadProviderInterface $paymentPayloadProvider,
        private PaymentHttpRequestProviderInterface $paymentHttpRequestProvider,
        private PaymentOrderProviderInterface $paymentOrderProvider,
        private PaymentApiClientProviderInterface $paymentApiClientProvider,
    ) {
    }

    public function isValid(PaymentRequestInterface $paymentRequest): bool
    {
        $payload = $this->paymentPayloadProvider->provideFromPaymentRequest(
            paymentRequest: $paymentRequest,
        );

        $payload->validateNotNull([
            'sessionId',
            'amount',
            'orderId',
            'methodId',
            'statement',
            'currency',
        ]);

        /** @var Request $httpRequest */
        $httpRequest = $this->paymentHttpRequestProvider->provide(
            paymentRequest: $paymentRequest,
        );

        Assert::notNull(
            value: $httpRequest,
            message: 'Http request cannot be null',
        );

        $order = $this->paymentOrderProvider->provide(
            paymentRequest: $paymentRequest,
        );

        /** @var Przelewy24 $przelewy24 */
        $przelewy24 = $this->paymentApiClientProvider->provideFromPaymentRequest(
            paymentRequest: $paymentRequest,
        );

        $notification = $przelewy24->handleWebhook(
            requestData: $httpRequest->toArray(),
        );

        return true === $notification->isSignValid(
            sessionId: $payload->sessionId(),
            amount: $payload->amount(),
            originAmount: $order->getTotal(),
            orderId: $payload->orderId(),
            methodId: $payload->methodId(),
            statement: $payload->statement(),
            currency: $payload->currency(),
        );
    }
}
