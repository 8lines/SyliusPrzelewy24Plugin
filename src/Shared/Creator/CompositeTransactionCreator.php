<?php

declare(strict_types=1);

namespace BitBag\SyliusPrzelewy24Plugin\Shared\Creator;

use Laminas\Stdlib\PriorityQueue;

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

    public function create(CreatableTransactionRequest $request): void
    {
        /** @var TransactionCreatorInterface $transactionCreator */
        foreach ($this->transactionCreators as $transactionCreator) {
            $transactionCreator->create($request);
        }
    }
}
