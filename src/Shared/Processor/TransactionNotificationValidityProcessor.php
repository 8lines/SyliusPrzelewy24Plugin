<?php

declare(strict_types=1);

namespace BitBag\SyliusPrzelewy24Plugin\Shared\Processor;

use BitBag\SyliusPrzelewy24Plugin\Shared\Checker\TransactionNotificationValidityCheckerInterface;
use Webmozart\Assert\Assert;

final readonly class TransactionNotificationValidityProcessor implements TransactionNotificationProcessorInterface
{
    public function __construct(
        private TransactionNotificationValidityCheckerInterface $transactionNotificationValidityChecker,
    ) {
    }

    public function process(NotificationRequestInterface $request): void
    {
        Assert::true(
            value: $this->transactionNotificationValidityChecker->isValid($request),
            message: 'Transaction notification is not valid.',
        );
    }
}
