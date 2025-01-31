<?php

declare(strict_types=1);

namespace BitBag\SyliusPrzelewy24Plugin\Shared\Processor;

use BitBag\SyliusPrzelewy24Plugin\Shared\Entity\TransactionalPaymentRequestInterface;
use Psr\Log\LoggerInterface;
use Sylius\Abstraction\StateMachine\StateMachineInterface;
use Sylius\Component\Payment\Model\PaymentRequestInterface;
use Sylius\Component\Payment\PaymentRequestTransitions;
use Sylius\Component\Payment\PaymentTransitions;

final readonly class PaymentRequestProcessor implements PaymentRequestProcessorInterface
{
    public function __construct(
        private StateMachineInterface $stateMachine,
        private LoggerInterface $logger,
    ) {
    }

    public function process(
        TransactionalPaymentRequestInterface $request,
        callable $action,
    ): void {
        $this->stateMachine->apply(
            subject: $request,
            graphName: PaymentRequestTransitions::GRAPH,
            transition: PaymentRequestTransitions::TRANSITION_PROCESS,
        );

        try {
            $action($request);

        } catch (\Exception $exception) {
            $this->logger->error(
                message: 'Payment request processing failed.',
                context: ['exception' => $exception],
            );

            $this->processException($request);
            return;
        }

        $this->stateMachine->apply(
            subject: $request,
            graphName: PaymentRequestTransitions::GRAPH,
            transition: PaymentRequestTransitions::TRANSITION_COMPLETE,
        );
    }

    private function processException(TransactionalPaymentRequestInterface $request): void
    {
        if (PaymentRequestInterface::ACTION_CAPTURE === $request->getAction()) {
            $this->stateMachine->apply(
                subject: $request->getPayment(),
                graphName: PaymentTransitions::GRAPH,
                transition: PaymentTransitions::TRANSITION_FAIL,
            );
        }

        $this->stateMachine->apply(
            subject: $request,
            graphName: PaymentRequestTransitions::GRAPH,
            transition: PaymentRequestTransitions::TRANSITION_FAIL,
        );
    }
}
