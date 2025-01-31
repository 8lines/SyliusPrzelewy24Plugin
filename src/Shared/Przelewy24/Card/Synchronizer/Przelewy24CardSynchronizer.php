<?php

declare(strict_types=1);

namespace BitBag\SyliusPrzelewy24Plugin\Shared\Przelewy24\Card\Synchronizer;

use BitBag\SyliusPrzelewy24Plugin\Shared\Provider\PaymentApiClientProviderInterface;
use BitBag\SyliusPrzelewy24Plugin\Shared\Synchronizer\SynchronizableRequestInterface;
use BitBag\SyliusPrzelewy24Plugin\Shared\Synchronizer\TransactionSynchronizerInterface;
use Przelewy24\Przelewy24;

final readonly class Przelewy24CardSynchronizer implements TransactionSynchronizerInterface
{
    /**
     * @param PaymentApiClientProviderInterface<Przelewy24> $paymentApiClientProvider
     */
    public function __construct(
        private PaymentApiClientProviderInterface $paymentApiClientProvider,
    ) {
    }

    public function synchronize(SynchronizableRequestInterface $request): void
    {
        $payload = $request->getTransactionPayload();
        $payload->validateNotNull(['orderId']);

        $przelewy24 = $this->paymentApiClientProvider->provideFromPaymentMethod(
            paymentMethod: $request->getPaymentMethod(),
        );

        $card = $przelewy24->cards()->info(
            orderId: $payload->orderId(),
        );

        $payload->withCardRefId(
            cardRefId: $card->refId(),
        );

        $request->setTransactionPayload($payload);
    }
}
