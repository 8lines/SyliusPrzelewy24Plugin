<?php

declare(strict_types=1);

namespace BitBag\SyliusPrzelewy24Plugin\Shared\Creator;

use Laminas\Stdlib\PriorityQueue;
use Sylius\Component\Payment\Model\PaymentRequestInterface;

final readonly class CompositeTransactionCreator implements TransactionCreatorInterface
{
    /**
     * @var PriorityQueue<TransactionCreatorInterface>
     */
    private PriorityQueue $transactionCreators;

    public function __construct()
    {
        $this->transactionCreators = new PriorityQueue();
    }

    public function addCreator(
        TransactionCreatorInterface $transactionCreator,
        int $priority = 0,
    ): void {
        $this->transactionCreators->insert(
            data: $transactionCreator,
            priority: $priority,
        );
    }

    public function create(PaymentRequestInterface $paymentRequest): void
    {
        /** @var TransactionCreatorInterface $transactionCreator */
        foreach ($this->transactionCreators as $transactionCreator) {
            $transactionCreator->create($paymentRequest);
        }
    }
}
