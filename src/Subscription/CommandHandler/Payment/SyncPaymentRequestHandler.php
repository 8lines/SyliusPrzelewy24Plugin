<?php

declare(strict_types=1);

namespace BitBag\SyliusPrzelewy24Plugin\Subscription\CommandHandler\Payment;

use BitBag\SyliusPrzelewy24Plugin\Shared\Entity\TransactionalPaymentRequestInterface;
use BitBag\SyliusPrzelewy24Plugin\Shared\Processor\PaymentRequestProcessorInterface;
use BitBag\SyliusPrzelewy24Plugin\Shared\Synchronizer\TransactionSynchronizerInterface;
use BitBag\SyliusPrzelewy24Plugin\Subscription\Command\Payment\SyncPaymentRequest;
use Sylius\Bundle\PaymentBundle\Provider\PaymentRequestProviderInterface;

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
        /** @var TransactionalPaymentRequestInterface $paymentRequest */
        $paymentRequest = $this->paymentRequestProvider->provide(
            command: $syncPaymentRequest,
        );

        $this->paymentRequestProcessor->process(
            request: $paymentRequest,
            action: fn (TransactionalPaymentRequestInterface $request) => $this->compositeTransactionSynchronizer->synchronize($request),
        );
    }
}
