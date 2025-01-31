<?php

declare(strict_types=1);

namespace BitBag\SyliusPrzelewy24Plugin\Subscription\Processor;

use BitBag\SyliusPrzelewy24Plugin\Shared\Processor\NotificationRequestInterface;
use BitBag\SyliusPrzelewy24Plugin\Shared\Processor\TransactionNotificationProcessorInterface;
use BitBag\SyliusPrzelewy24Plugin\Shared\Refunder\TransactionRefunderInterface;
use BitBag\SyliusPrzelewy24Plugin\Subscription\Entity\AddCardRequestInterface;
use Webmozart\Assert\Assert;

final readonly class AddCardNotificationTransactionRefundingProcessor implements TransactionNotificationProcessorInterface
{
    public function __construct(
        private TransactionRefunderInterface $transactionRefunder,
    ) {
    }

    public function process(NotificationRequestInterface $request): void
    {
        /** @var AddCardRequestInterface $request */

        Assert::isInstanceOf(
            value: $request,
            class: AddCardRequestInterface::class,
            message: 'Request must be instance of %2$s, but is %s.',
        );

        $this->transactionRefunder->refund($request);
    }
}
