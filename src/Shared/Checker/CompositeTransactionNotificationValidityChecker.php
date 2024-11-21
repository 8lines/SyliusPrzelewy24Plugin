<?php

declare(strict_types=1);

namespace BitBag\SyliusPrzelewy24Plugin\Shared\Checker;

use BitBag\SyliusPrzelewy24Plugin\Shared\Synchronizer\TransactionSynchronizerInterface;
use Laminas\Stdlib\PriorityQueue;
use Sylius\Component\Payment\Model\PaymentRequestInterface;

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

    public function isValid(PaymentRequestInterface $paymentRequest): bool
    {
        $this->compositeTransactionSynchronizer->synchronize(
            paymentRequest: $paymentRequest,
        );

        /** @var TransactionNotificationValidityCheckerInterface $notificationValidityChecker */
        foreach ($this->notificationValidityCheckers as $notificationValidityChecker) {
            if (false === $notificationValidityChecker->isValid($paymentRequest)) {
                return false;
            }
        }

        return true;
    }
}
