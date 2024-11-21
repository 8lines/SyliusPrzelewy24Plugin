<?php

declare(strict_types=1);

namespace BitBag\SyliusPrzelewy24Plugin\Subscription\CommandHandler\Payment;

use BitBag\SyliusPrzelewy24Plugin\Shared\Processor\PaymentRequestProcessorInterface;
use BitBag\SyliusPrzelewy24Plugin\Subscription\Command\Payment\SyncPaymentRequest;
use BitBag\SyliusPrzelewy24Plugin\Shared\Synchronizer\TransactionSynchronizerInterface;
use Sylius\Bundle\PaymentBundle\Provider\PaymentRequestProviderInterface;
use Sylius\Component\Payment\Model\PaymentRequestInterface;

final readonly class SyncPaymentRequestHandler
{
    public function __construct(
        private PaymentRequestProviderInterface $paymentRequestProvider,
        private PaymentRequestProcessorInterface $paymentRequestProcessor,
        private TransactionSynchronizerInterface $compositeTransactionSynchronizer,
    ) {
    }

    public function __invoke(SyncPaymentRequest $syncPaymentRequest): void
    {
        $paymentRequest = $this->paymentRequestProvider->provide(
            command: $syncPaymentRequest,
        );

        $this->paymentRequestProcessor->process(
            paymentRequest: $paymentRequest,
            action: fn(PaymentRequestInterface $paymentRequest) => $this->compositeTransactionSynchronizer->synchronize($paymentRequest),
        );
    }
}
