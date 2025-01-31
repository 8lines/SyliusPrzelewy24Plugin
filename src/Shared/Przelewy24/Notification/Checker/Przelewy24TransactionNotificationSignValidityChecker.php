<?php

declare(strict_types=1);

namespace BitBag\SyliusPrzelewy24Plugin\Shared\Przelewy24\Notification\Checker;

use BitBag\SyliusPrzelewy24Plugin\Shared\Checker\TransactionNotificationValidityCheckerInterface;
use BitBag\SyliusPrzelewy24Plugin\Shared\Checker\ValidableNotificationRequestInterface;
use BitBag\SyliusPrzelewy24Plugin\Shared\Denormalizer\SymfonyRequestDenormalizerInterface;
use BitBag\SyliusPrzelewy24Plugin\Shared\Provider\PaymentApiClientProviderInterface;
use Przelewy24\Przelewy24;
use Webmozart\Assert\Assert;

final readonly class Przelewy24TransactionNotificationSignValidityChecker implements TransactionNotificationValidityCheckerInterface
{
    /**
     * @param PaymentApiClientProviderInterface<Przelewy24> $paymentApiClientProvider
     */
    public function __construct(
        private SymfonyRequestDenormalizerInterface $symfonyRequestDenormalizer,
        private PaymentApiClientProviderInterface $paymentApiClientProvider,
    ) {
    }

    public function isValid(ValidableNotificationRequestInterface $request): bool
    {
        $payload = $request->getTransactionPayload();
        $payload->validateNotNull([
            'sessionId',
            'amount',
            'orderId',
            'methodId',
            'statement',
            'currency',
        ]);

        $httpRequest = $this->symfonyRequestDenormalizer->denormalize(
            httpRequest: $request->getNormalizedHttpRequest(),
        );

        Assert::notNull(
            value: $httpRequest,
            message: 'Http request cannot be null',
        );

        $przelewy24 = $this->paymentApiClientProvider->provideFromPaymentMethod(
            paymentMethod: $request->getPaymentMethod(),
        );

        $notification = $przelewy24->handleWebhook(
            requestData: $httpRequest->toArray(),
        );

        return true === $notification->isSignValid(
            sessionId: $payload->sessionId(),
            amount: $payload->amount(),
            originAmount: $request->getOrderTotal(),
            orderId: $payload->orderId(),
            methodId: $payload->methodId(),
            statement: $payload->statement(),
            currency: $payload->currency(),
        );
    }
}
