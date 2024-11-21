<?php

declare(strict_types=1);

namespace BitBag\SyliusPrzelewy24Plugin\Shared\Przelewy24\StateResolver;

use BitBag\SyliusPrzelewy24Plugin\Shared\Provider\PaymentApiClientProviderInterface;
use BitBag\SyliusPrzelewy24Plugin\Shared\Provider\PaymentPayloadProviderInterface;
use BitBag\SyliusPrzelewy24Plugin\Shared\Przelewy24\Transaction\Enum\Przelewy24TransactionStatus;
use BitBag\SyliusPrzelewy24Plugin\Shared\StateResolver\PaymentStateResolverInterface;
use Przelewy24\Przelewy24;
use Sylius\Abstraction\StateMachine\StateMachineInterface;
use Sylius\Component\Core\Model\PaymentInterface;
use Sylius\Component\Payment\Model\PaymentRequestInterface;
use Sylius\Component\Payment\PaymentTransitions;

final readonly class Przelewy24PaymentStateResolver implements PaymentStateResolverInterface
{
    public function __construct(
        private PaymentPayloadProviderInterface $paymentPayloadProvider,
        private PaymentApiClientProviderInterface $paymentApiClientProvider,
        private StateMachineInterface $stateMachine,
    ) {
    }

    public function resolve(PaymentRequestInterface $paymentRequest): void
    {
        $payload = $this->paymentPayloadProvider->provideFromPaymentRequest(
            paymentRequest: $paymentRequest,
        );

        $payload->validateNotNull(['sessionId']);

        /** @var Przelewy24 $przelewy24 */
        $przelewy24 = $this->paymentApiClientProvider->provideFromPaymentRequest(
            paymentRequest: $paymentRequest,
        );

        try {
            $transaction = $przelewy24->transactions()->find(
                sessionId: $payload->sessionId(),
            );
        } catch (\Exception $exception) {
            return;
        }

        /** @var PaymentInterface $payment */
        $payment = $paymentRequest->getPayment();

        $status = Przelewy24TransactionStatus::fromSdkTransactionStatus(
            status: $transaction->status(),
        );

        $transition = match($status) {
            Przelewy24TransactionStatus::NO_PAYMENT => PaymentTransitions::TRANSITION_PROCESS,
            Przelewy24TransactionStatus::PAYMENT_COMPLETED => PaymentTransitions::TRANSITION_COMPLETE,
            Przelewy24TransactionStatus::PAYMENT_RETURNED => PaymentTransitions::TRANSITION_REFUND,
            Przelewy24TransactionStatus::PAYMENT_FAILED => PaymentTransitions::TRANSITION_FAIL,
        };

        $this->stateMachine->apply(
            subject: $payment,
            graphName: PaymentTransitions::GRAPH,
            transition: $transition,
        );
    }
}
