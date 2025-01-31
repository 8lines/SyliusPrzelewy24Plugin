<?php

declare(strict_types=1);

namespace BitBag\SyliusPrzelewy24Plugin\OneTime\CommandHandler\Payment;

use BitBag\SyliusPrzelewy24Plugin\OneTime\Command\Payment\NotifyPaymentRequest;
use BitBag\SyliusPrzelewy24Plugin\Shared\Entity\TransactionalPaymentRequestInterface;
use BitBag\SyliusPrzelewy24Plugin\Shared\Processor\PaymentRequestProcessorInterface;
use BitBag\SyliusPrzelewy24Plugin\Shared\Processor\TransactionNotificationProcessorInterface;
use Sylius\Bundle\PaymentBundle\Provider\PaymentRequestProviderInterface;

final readonly class NotifyPaymentRequestHandler
{
    public function __construct(
        private PaymentRequestProviderInterface $paymentRequestProvider,
        private PaymentRequestProcessorInterface $paymentRequestProcessor,
        private TransactionNotificationProcessorInterface $compositeTransactionNotificationProcessor,
    ) {
    }

    public function __invoke(NotifyPaymentRequest $notifyPaymentRequest): void
    {
        /** @var TransactionalPaymentRequestInterface $paymentRequest */
        $paymentRequest = $this->paymentRequestProvider->provide(
            command: $notifyPaymentRequest,
        );

        $this->paymentRequestProcessor->process(
            request: $paymentRequest,
            action: fn (TransactionalPaymentRequestInterface $request) => $this->compositeTransactionNotificationProcessor->process($request),
        );
    }
}
