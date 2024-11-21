<?php

declare(strict_types=1);

namespace BitBag\SyliusPrzelewy24Plugin\Shared\Processor;

use Sylius\Abstraction\StateMachine\StateMachineInterface;
use Sylius\Component\Payment\Model\PaymentRequestInterface;
use Sylius\Component\Payment\PaymentRequestTransitions;
use Sylius\Component\Payment\PaymentTransitions;

final readonly class PaymentRequestProcessor implements PaymentRequestProcessorInterface
{
    public function __construct(
        private StateMachineInterface $stateMachine,
    ) {
    }

    public function process(
        PaymentRequestInterface $paymentRequest,
        callable $action,
    ): void {
        $this->stateMachine->apply(
            subject: $paymentRequest,
            graphName: PaymentRequestTransitions::GRAPH,
            transition: PaymentRequestTransitions::TRANSITION_PROCESS,
        );

        try {
            $action($paymentRequest);

        } catch (\Exception) {
            $this->processException($paymentRequest);
            return;
        }

        $this->stateMachine->apply(
            subject: $paymentRequest,
            graphName: PaymentRequestTransitions::GRAPH,
            transition: PaymentRequestTransitions::TRANSITION_COMPLETE,
        );
    }

    private function processException(PaymentRequestInterface $paymentRequest): void
    {
        if (PaymentRequestInterface::ACTION_CAPTURE === $paymentRequest->getAction()) {
            $this->stateMachine->apply(
                subject: $paymentRequest->getPayment(),
                graphName: PaymentTransitions::GRAPH,
                transition: PaymentTransitions::TRANSITION_FAIL,
            );
        }

        $this->stateMachine->apply(
            subject: $paymentRequest,
            graphName: PaymentRequestTransitions::GRAPH,
            transition: PaymentRequestTransitions::TRANSITION_FAIL,
        );
    }
}
