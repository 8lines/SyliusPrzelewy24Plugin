<?php

declare(strict_types=1);

namespace BitBag\SyliusPrzelewy24Plugin\Subscription\CommandHandler\Subscription;

use BitBag\SyliusPrzelewy24Plugin\Shared\Processor\TransactionNotificationProcessorInterface;
use BitBag\SyliusPrzelewy24Plugin\Subscription\Command\Subscription\HandleAddCardNotification;
use BitBag\SyliusPrzelewy24Plugin\Subscription\Entity\AddCardRequestInterface;
use BitBag\SyliusPrzelewy24Plugin\Subscription\Processor\AddCardRequestProcessorInterface;
use BitBag\SyliusPrzelewy24Plugin\Subscription\Repository\AddCardRequestRepositoryInterface;
use Webmozart\Assert\Assert;

final readonly class HandleAddCardNotificationHandler
{
    public function __construct(
        private AddCardRequestRepositoryInterface $addCardRequestRepository,
        private AddCardRequestProcessorInterface $addCardRequestProcessor,
        private TransactionNotificationProcessorInterface $compositeTransactionNotificationProcessor,
    ) {
    }

    public function __invoke(HandleAddCardNotification $command): void
    {
        $addCardRequest = $this->addCardRequestRepository->findOneBy([
            'hash' => $command->getHash(),
            'action' => AddCardRequestInterface::ACTION_NOTIFY,
            'state' => AddCardRequestInterface::STATE_NEW,
        ]);

        Assert::notNull(
            value: $addCardRequest,
            message: 'Add card request with hash %s not found.',
        );

        $this->addCardRequestProcessor->process(
            request: $addCardRequest,
            action: fn (AddCardRequestInterface $addCardRequest) => $this->compositeTransactionNotificationProcessor->process($addCardRequest),
        );

        $this->addCardRequestRepository->add($addCardRequest);
    }
}
