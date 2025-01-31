<?php

declare(strict_types=1);

namespace BitBag\SyliusPrzelewy24Plugin\Subscription\CommandHandler\Payment;

use BitBag\SyliusPrzelewy24Plugin\Shared\Charger\CardChargerInterface;
use BitBag\SyliusPrzelewy24Plugin\Shared\Creator\TransactionCreatorInterface;
use BitBag\SyliusPrzelewy24Plugin\Shared\Entity\TransactionalPaymentRequestInterface;
use BitBag\SyliusPrzelewy24Plugin\Shared\Payload\PaymentPayload;
use BitBag\SyliusPrzelewy24Plugin\Shared\Processor\PaymentRequestProcessorInterface;
use BitBag\SyliusPrzelewy24Plugin\Subscription\Command\Payment\CapturePaymentRequest;
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
        private CardChargerInterface $cardCharger,
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
            action: fn (TransactionalPaymentRequestInterface $request) => $this->process($request),
        );
    }

    private function process(TransactionalPaymentRequestInterface $request): void
    {
        $this->compositeTransactionCreator->create(
            request: $request,
        );

        /** @var PaymentPayload $payload */
        $payload = $request->getTransactionPayload();

        if (false === $payload->initializingSubscription() && false === $payload->payWithExistingCard()) {
            throw new \InvalidArgumentException('Not initial recurring transaction must be paid with existing card');
        }

        if (false === $payload->initializingSubscription() && true === $payload->payWithExistingCard()) {
            $this->cardCharger->charge($request);
        }

        if (true === $payload->initializingSubscription() && true === $payload->payWithExistingCard()) {
            $this->cardCharger->charge($request);
            $request->setResponseData([]);
        }
    }
}
