<?php

declare(strict_types=1);

namespace BitBag\SyliusPrzelewy24Plugin\Subscription\CommandHandler\Payment;

use BitBag\SyliusPrzelewy24Plugin\Shared\Charger\CardChargerInterface;
use BitBag\SyliusPrzelewy24Plugin\Shared\Processor\PaymentRequestProcessorInterface;
use BitBag\SyliusPrzelewy24Plugin\Shared\Provider\PaymentPayloadProviderInterface;
use BitBag\SyliusPrzelewy24Plugin\Subscription\Command\Payment\CapturePaymentRequest;
use BitBag\SyliusPrzelewy24Plugin\Shared\Creator\TransactionCreatorInterface;
use Sylius\Abstraction\StateMachine\StateMachineInterface;
use Sylius\Bundle\PaymentBundle\Provider\PaymentRequestProviderInterface;
use Sylius\Component\Payment\Model\PaymentRequestInterface;
use Sylius\Component\Payment\PaymentTransitions;

final readonly class CapturePaymentRequestHandler
{
    public function __construct(
        private PaymentRequestProviderInterface $paymentRequestProvider,
        private StateMachineInterface $stateMachine,
        private PaymentRequestProcessorInterface $paymentRequestProcessor,
        private TransactionCreatorInterface $compositeTransactionCreator,
        private PaymentPayloadProviderInterface $paymentPayloadProvider,
        private CardChargerInterface $cardCharger,
    ) {
    }

    public function __invoke(CapturePaymentRequest $capturePaymentRequest): void
    {
        $paymentRequest = $this->paymentRequestProvider->provide(
            command: $capturePaymentRequest,
        );

        $this->stateMachine->apply(
            subject: $paymentRequest->getPayment(),
            graphName: PaymentTransitions::GRAPH,
            transition: PaymentTransitions::TRANSITION_PROCESS,
        );

        $this->paymentRequestProcessor->process(
            paymentRequest: $paymentRequest,
            action: fn(PaymentRequestInterface $paymentRequest) => $this->process($paymentRequest),
        );
    }

    private function process(PaymentRequestInterface $paymentRequest): void
    {
        $this->compositeTransactionCreator->create(
            paymentRequest: $paymentRequest,
        );

        $payload = $this->paymentPayloadProvider->provideFromPaymentRequest(
            paymentRequest: $paymentRequest,
        );

        if (false === $payload->initializingSubscription() && false === $payload->payWithExistingCard()) {
            throw new \InvalidArgumentException('Not initial recurring transaction must be paid with existing card');
        }

        if (false === $payload->initializingSubscription() && true === $payload->payWithExistingCard()) {
            $this->cardCharger->charge($paymentRequest);
        }

        if (true === $payload->initializingSubscription() && true === $payload->payWithExistingCard()) {
            $this->cardCharger->charge($paymentRequest);
            $paymentRequest->setResponseData([]);
        }
    }
}
