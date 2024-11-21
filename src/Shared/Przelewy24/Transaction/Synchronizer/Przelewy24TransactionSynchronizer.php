<?php

declare(strict_types=1);

namespace BitBag\SyliusPrzelewy24Plugin\Shared\Przelewy24\Transaction\Synchronizer;

use BitBag\SyliusPrzelewy24Plugin\Shared\Assigner\PaymentPayloadAssignerInterface;
use BitBag\SyliusPrzelewy24Plugin\Shared\Provider\PaymentApiClientProviderInterface;
use BitBag\SyliusPrzelewy24Plugin\Shared\Provider\PaymentPayloadProviderInterface;
use BitBag\SyliusPrzelewy24Plugin\Shared\Synchronizer\TransactionSynchronizerInterface;
use Przelewy24\Przelewy24;
use Sylius\Component\Payment\Model\PaymentRequestInterface;

final readonly class Przelewy24TransactionSynchronizer implements TransactionSynchronizerInterface
{
    public function __construct(
        private PaymentPayloadProviderInterface $paymentPayloadProvider,
        private PaymentApiClientProviderInterface $paymentApiClientProvider,
        private PaymentPayloadAssignerInterface $paymentPayloadAssigner,
    ) {
    }

    public function synchronize(PaymentRequestInterface $paymentRequest): void
    {
        $payload = $this->paymentPayloadProvider->provideFromPaymentRequest(
            paymentRequest: $paymentRequest,
        );

        $payload->validateNotNull(['sessionId']);

        /** @var Przelewy24 $przelewy24 */
        $przelewy24 = $this->paymentApiClientProvider->provideFromPaymentRequest(
            paymentRequest:  $paymentRequest,
        );

        $transaction = $przelewy24->transactions()->find(
            sessionId: $payload->sessionId(),
        );

        $payload->withOrderId($transaction->orderId());
        $payload->withAmount($transaction->amount());
        $payload->withCurrency($transaction->currency());
        $payload->withStatement(\lcfirst($transaction->statement()));
        $payload->withMethodId($transaction->paymentMethod());

        $this->paymentPayloadAssigner->assign(
            paymentRequest: $paymentRequest,
            payload: $payload,
        );
    }
}
