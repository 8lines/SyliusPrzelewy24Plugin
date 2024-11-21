<?php

declare(strict_types=1);

namespace BitBag\SyliusPrzelewy24Plugin\Shared\Synchronizer;

use Laminas\Stdlib\PriorityQueue;
use Sylius\Component\Payment\Model\PaymentRequestInterface;

final readonly class CompositeTransactionSynchronizer implements TransactionSynchronizerInterface
{
    /**
     * @var PriorityQueue<TransactionSynchronizerInterface>
     */
    private PriorityQueue $transactionSynchronizers;

    public function __construct()
    {
        $this->transactionSynchronizers = new PriorityQueue();
    }

    public function addSynchronizer(
        TransactionSynchronizerInterface $transactionSynchronizer,
        int $priority,
    ): void {
        $this->transactionSynchronizers->insert(
            data: $transactionSynchronizer,
            priority: $priority,
        );
    }

    public function synchronize(PaymentRequestInterface $paymentRequest): void
    {
        /** @var TransactionSynchronizerInterface $transactionSynchronizer */
        foreach ($this->transactionSynchronizers as $transactionSynchronizer) {
            $transactionSynchronizer->synchronize($paymentRequest);
        }
    }
}
