<?php

declare(strict_types=1);

namespace BitBag\SyliusPrzelewy24Plugin\Shared\Checker;

use BitBag\SyliusPrzelewy24Plugin\Shared\Synchronizer\TransactionSynchronizerInterface;
use Laminas\Stdlib\PriorityQueue;

final readonly class CompositeTransactionNotificationValidityChecker implements TransactionNotificationValidityCheckerInterface
{
    /**
     * @var PriorityQueue<TransactionNotificationValidityCheckerInterface>
     */
    private PriorityQueue $notificationValidityCheckers;

    public function __construct(
        private TransactionSynchronizerInterface $compositeTransactionSynchronizer,
    ) {
        $this->notificationValidityCheckers = new PriorityQueue();
    }

    public function addChecker(
        TransactionNotificationValidityCheckerInterface $notificationValidityChecker,
        int $priority = 0,
    ): void {
        $this->notificationValidityCheckers->insert(
            data: $notificationValidityChecker,
            priority: $priority,
        );
    }

    public function isValid(ValidableNotificationRequestInterface $request): bool
    {
        $this->compositeTransactionSynchronizer->synchronize(
            request: $request,
        );

        /** @var TransactionNotificationValidityCheckerInterface $notificationValidityChecker */
        foreach ($this->notificationValidityCheckers as $notificationValidityChecker) {
            if (false === $notificationValidityChecker->isValid($request)) {
                return false;
            }
        }

        return true;
    }
}
