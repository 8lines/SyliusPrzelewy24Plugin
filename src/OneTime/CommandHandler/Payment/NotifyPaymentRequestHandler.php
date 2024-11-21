<?php

declare(strict_types=1);

namespace BitBag\SyliusPrzelewy24Plugin\OneTime\CommandHandler\Payment;

use BitBag\SyliusPrzelewy24Plugin\OneTime\Command\Payment\NotifyPaymentRequest;
use BitBag\SyliusPrzelewy24Plugin\Shared\Processor\PaymentRequestProcessorInterface;
use BitBag\SyliusPrzelewy24Plugin\Shared\Processor\TransactionNotificationProcessorInterface;
use Sylius\Bundle\PaymentBundle\Provider\PaymentRequestProviderInterface;
use Sylius\Component\Payment\Model\PaymentRequestInterface;

final readonly class NotifyPaymentRequestHandler
{
    public function __construct(
        private PaymentRequestProviderInterface $paymentRequestProvider,
        private PaymentRequestProcessorInterface $paymentRequestProcessor,
        private TransactionNotificationProcessorInterface $transactionNotificationProcessor,
    ) {
    }

    public function __invoke(NotifyPaymentRequest $notifyPaymentRequest): void
    {
        $paymentRequest = $this->paymentRequestProvider->provide(
            command: $notifyPaymentRequest,
        );

        $this->paymentRequestProcessor->process(
            paymentRequest: $paymentRequest,
            action: fn(PaymentRequestInterface $paymentRequest) => $this->transactionNotificationProcessor->process($paymentRequest),
        );
    }
}
