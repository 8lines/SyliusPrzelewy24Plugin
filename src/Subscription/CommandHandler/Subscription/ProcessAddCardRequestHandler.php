<?php

declare(strict_types=1);

namespace BitBag\SyliusPrzelewy24Plugin\Subscription\CommandHandler\Subscription;

use BitBag\SyliusPrzelewy24Plugin\Shared\Creator\TransactionCreatorInterface;
use BitBag\SyliusPrzelewy24Plugin\Subscription\Command\Subscription\ProcessAddCardRequest;
use BitBag\SyliusPrzelewy24Plugin\Subscription\Entity\AddCardRequestInterface;
use BitBag\SyliusPrzelewy24Plugin\Subscription\Processor\AddCardRequestProcessorInterface;
use BitBag\SyliusPrzelewy24Plugin\Subscription\Repository\AddCardRequestRepositoryInterface;
use Webmozart\Assert\Assert;

final readonly class ProcessAddCardRequestHandler
{
    public function __construct(
        private AddCardRequestRepositoryInterface $addCardRequestRepository,
        private AddCardRequestProcessorInterface $addCardRequestProcessor,
        private TransactionCreatorInterface $compositeAddCardTransactionCreator,
    ) {
    }

    public function __invoke(ProcessAddCardRequest $command): void
    {
        $addCardRequest = $this->addCardRequestRepository->findOneBy([
            'hash' => $command->getHash(),
            'action' => AddCardRequestInterface::ACTION_CAPTURE,
            'state' => AddCardRequestInterface::STATE_NEW,
        ]);

        Assert::notNull(
            value: $addCardRequest,
            message: 'Add card request with hash %s not found.',
        );

        $this->addCardRequestProcessor->process(
            request: $addCardRequest,
            action: fn (AddCardRequestInterface $addCardRequest) => $this->compositeAddCardTransactionCreator->create($addCardRequest),
        );

        $this->addCardRequestRepository->add($addCardRequest);
    }
}
