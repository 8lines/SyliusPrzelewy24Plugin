<?php

declare(strict_types=1);

namespace BitBag\SyliusPrzelewy24Plugin\Shared\Processor;

use Laminas\Stdlib\PriorityQueue;

final readonly class CompositeTransactionNotificationProcessor implements TransactionNotificationProcessorInterface
{
    /**
     * @var PriorityQueue<TransactionNotificationProcessorInterface>
     */
    private PriorityQueue $transactionNotificationProcessors;

    public function __construct()
    {
        $this->transactionNotificationProcessors = new PriorityQueue();
    }

    public function addProcessor(
        TransactionNotificationProcessorInterface $transactionNotificationProcessor,
        int $priority,
    ): void {
        $this->transactionNotificationProcessors->insert(
            data: $transactionNotificationProcessor,
            priority: $priority,
        );
    }

    public function process(NotificationRequestInterface $request): void
    {
        /** @var TransactionNotificationProcessorInterface $transactionNotificationProcessor */
        foreach ($this->transactionNotificationProcessors as $transactionNotificationProcessor) {
            $transactionNotificationProcessor->process($request);
        }
    }
}
