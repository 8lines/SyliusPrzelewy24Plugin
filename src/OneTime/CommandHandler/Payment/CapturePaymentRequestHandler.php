<?php

declare(strict_types=1);

namespace BitBag\SyliusPrzelewy24Plugin\OneTime\CommandHandler\Payment;

use BitBag\SyliusPrzelewy24Plugin\OneTime\Command\Payment\CapturePaymentRequest;
use BitBag\SyliusPrzelewy24Plugin\Shared\Creator\TransactionCreatorInterface;
use BitBag\SyliusPrzelewy24Plugin\Shared\Processor\PaymentRequestProcessorInterface;
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
            action: fn(PaymentRequestInterface $paymentRequest) => $this->compositeTransactionCreator->create($paymentRequest),
        );
    }
}
