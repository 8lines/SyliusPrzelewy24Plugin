<?php

declare(strict_types=1);

namespace BitBag\SyliusPrzelewy24Plugin\Shared\Przelewy24\Transaction\Creator;

use BitBag\SyliusPrzelewy24Plugin\Shared\Creator\TransactionCreatorInterface;
use Przelewy24\Enums\TransactionChannel;
use Sylius\Component\Payment\Model\PaymentRequestInterface;

final readonly class Przelewy24SubscriptionTransactionCreator implements TransactionCreatorInterface
{
    use Przelewy24TransactionCreatorTrait;

    public function create(PaymentRequestInterface $paymentRequest): void
    {
        $payload = $this->paymentPayloadProvider->provideFromPaymentRequest(
            paymentRequest: $paymentRequest,
        );

        $payload->validateNotNull([
            'initializingSubscription',
            'payWithExistingCard',
        ]);

        if (false === $payload->initializingSubscription() || true === $payload->payWithExistingCard()) {
            $payload->validateNotNull(['cardRefId']);
        }

        $this->registerTransaction(
            paymentRequest: $paymentRequest,
            channel: TransactionChannel::CARDS_ONLY,
            methodRefId: $payload->cardRefId(),
        );
    }
}
