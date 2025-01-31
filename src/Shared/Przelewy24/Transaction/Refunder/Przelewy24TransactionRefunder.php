<?php

declare(strict_types=1);

namespace BitBag\SyliusPrzelewy24Plugin\Shared\Przelewy24\Transaction\Refunder;

use BitBag\SyliusPrzelewy24Plugin\Shared\Provider\PaymentApiClientProviderInterface;
use BitBag\SyliusPrzelewy24Plugin\Shared\Refunder\RefundableRequestInterface;
use BitBag\SyliusPrzelewy24Plugin\Shared\Refunder\TransactionRefunderInterface;
use Przelewy24\Api\Requests\Items\RefundItem;
use Przelewy24\Przelewy24;
use Symfony\Component\Uid\Uuid;

final readonly class Przelewy24TransactionRefunder implements TransactionRefunderInterface
{
    /**
     * @param PaymentApiClientProviderInterface<Przelewy24> $paymentApiClientProvider
     */
    public function __construct(
        private PaymentApiClientProviderInterface $paymentApiClientProvider,
    ) {
    }

    public function refund(RefundableRequestInterface $request): void
    {
        $payload = $request->getTransactionPayload();
        $payload->validateNotNull([
            'sessionId',
            'orderId',
            'amount',
        ]);

        $przelewy24 = $this->paymentApiClientProvider->provideFromPaymentMethod(
            paymentMethod: $request->getPaymentMethod(),
        );

        $przelewy24->transactions()->refund(
            requestId: Uuid::v4()->toString(),
            refundsId: Uuid::v4()->toString(),
            refunds: [
                new RefundItem(
                    orderId: $payload->orderId(),
                    sessionId: $payload->sessionId(),
                    amount: $payload->amount(),
                ),
            ],
        );
    }
}
