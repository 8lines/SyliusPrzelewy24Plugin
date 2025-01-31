<?php

declare(strict_types=1);

namespace BitBag\SyliusPrzelewy24Plugin\Shared\Przelewy24\Card\Charger;

use BitBag\SyliusPrzelewy24Plugin\Shared\Charger\CardChargeableRequestInterface;
use BitBag\SyliusPrzelewy24Plugin\Shared\Charger\CardChargerInterface;
use BitBag\SyliusPrzelewy24Plugin\Shared\Provider\PaymentApiClientProviderInterface;
use Przelewy24\Przelewy24;

final readonly class Przelewy24CardCharger implements CardChargerInterface
{
    /**
     * @param PaymentApiClientProviderInterface<Przelewy24> $paymentApiClientProvider
     */
    public function __construct(
        private PaymentApiClientProviderInterface $paymentApiClientProvider,
    ) {
    }

    public function charge(CardChargeableRequestInterface $request): void
    {
        $payload = $request->getTransactionPayload();
        $payload->validateNotNull(['transactionToken']);

        $przelewy24 = $this->paymentApiClientProvider->provideFromPaymentMethod(
            paymentMethod: $request->getPaymentMethod(),
        );

        $przelewy24->cards()->charge(
            token: $payload->transactionToken(),
        );
    }
}
