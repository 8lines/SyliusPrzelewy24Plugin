<?php

declare(strict_types=1);

namespace BitBag\SyliusPrzelewy24Plugin\OneTime\CommandHandler\Payment;

use BitBag\SyliusPrzelewy24Plugin\OneTime\Command\Payment\CapturePaymentRequest;
use BitBag\SyliusPrzelewy24Plugin\Shared\Creator\TransactionCreatorInterface;
use BitBag\SyliusPrzelewy24Plugin\Shared\Entity\TransactionalPaymentRequestInterface;
use BitBag\SyliusPrzelewy24Plugin\Shared\Processor\PaymentRequestProcessorInterface;
use Sylius\Abstraction\StateMachine\StateMachineInterface;
use Sylius\Bundle\PaymentBundle\Provider\PaymentRequestProviderInterface;
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
        /** @var TransactionalPaymentRequestInterface $paymentRequest */
        $paymentRequest = $this->paymentRequestProvider->provide(
            command: $capturePaymentRequest,
        );

        $this->stateMachine->apply(
            subject: $paymentRequest->getPayment(),
            graphName: PaymentTransitions::GRAPH,
            transition: PaymentTransitions::TRANSITION_PROCESS,
        );

        $this->paymentRequestProcessor->process(
            request: $paymentRequest,
            action: fn (TransactionalPaymentRequestInterface $request) => $this->compositeTransactionCreator->create($request),
        );
    }
}
