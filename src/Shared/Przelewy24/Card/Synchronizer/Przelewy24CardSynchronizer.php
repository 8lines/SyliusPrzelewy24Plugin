<?php

declare(strict_types=1);

namespace BitBag\SyliusPrzelewy24Plugin\Shared\Przelewy24\Card\Synchronizer;

use BitBag\SyliusPrzelewy24Plugin\Shared\Assigner\PaymentPayloadAssignerInterface;
use BitBag\SyliusPrzelewy24Plugin\Shared\Provider\PaymentApiClientProviderInterface;
use BitBag\SyliusPrzelewy24Plugin\Shared\Provider\PaymentPayloadProviderInterface;
use BitBag\SyliusPrzelewy24Plugin\Shared\Synchronizer\TransactionSynchronizerInterface;
use Przelewy24\Przelewy24;
use Sylius\Component\Payment\Model\PaymentRequestInterface;

final readonly class Przelewy24CardSynchronizer implements TransactionSynchronizerInterface
{
    public function __construct(
        private PaymentApiClientProviderInterface $paymentApiClientProvider,
        private PaymentPayloadProviderInterface $paymentPayloadProvider,
        private PaymentPayloadAssignerInterface $paymentPayloadAssigner,
    ) {
    }

    public function synchronize(PaymentRequestInterface $paymentRequest): void
    {
        $payload = $this->paymentPayloadProvider->provideFromPaymentRequest(
            paymentRequest: $paymentRequest,
        );

        $payload->validateNotNull(['orderId']);

        /** @var Przelewy24 $przelewy24 */
        $przelewy24 = $this->paymentApiClientProvider->provideFromPaymentRequest(
            paymentRequest:  $paymentRequest,
        );

        $card = $przelewy24->cards()->info(
            orderId: $payload->orderId(),
        );


        $payload->withcardRefId(
            cardRefId: $card->refId(),
        );

        $this->paymentPayloadAssigner->assign(
            paymentRequest: $paymentRequest,
            payload: $payload,
        );
    }
}
