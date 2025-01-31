<?php

declare(strict_types=1);

namespace BitBag\SyliusPrzelewy24Plugin\Shared\Assigner;

use Laminas\Stdlib\PriorityQueue;

final readonly class CompositeTransactionPayloadSessionAssigner implements TransactionPayloadDataAssignerInterface
{
    /**
     * @var PriorityQueue<TransactionPayloadDataAssignerInterface>
     */
    private PriorityQueue $sessionAssigners;

    public function __construct()
    {
        $this->sessionAssigners = new PriorityQueue();
    }

    public function addAssigner(
        TransactionPayloadDataAssignerInterface $paymentPayloadGeneratedItemAssigner,
        int $priority = 0,
    ): void {
        $this->sessionAssigners->insert(
            data: $paymentPayloadGeneratedItemAssigner,
            priority: $priority,
        );
    }

    public function assign(PayloadAssignableRequestInterface $request): void
    {
        /** @var TransactionPayloadDataAssignerInterface $sessionAssigner */
        foreach ($this->sessionAssigners as $sessionAssigner) {
            $sessionAssigner->assign($request);
        }
    }
}
