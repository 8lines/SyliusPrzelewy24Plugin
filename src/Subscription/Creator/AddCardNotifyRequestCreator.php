<?php

declare(strict_types=1);

namespace BitBag\SyliusPrzelewy24Plugin\Subscription\Creator;

use BitBag\SyliusPrzelewy24Plugin\Shared\Creator\CreatableTransactionRequest;
use BitBag\SyliusPrzelewy24Plugin\Shared\Creator\TransactionCreatorInterface;
use BitBag\SyliusPrzelewy24Plugin\Subscription\Assigner\AddCardPayloadNotifyRequestHashAssignerInterface;
use BitBag\SyliusPrzelewy24Plugin\Subscription\Entity\AddCardRequestInterface;
use BitBag\SyliusPrzelewy24Plugin\Subscription\Factory\AddCardRequestFactoryInterface;
use BitBag\SyliusPrzelewy24Plugin\Subscription\Repository\AddCardRequestRepositoryInterface;
use Webmozart\Assert\Assert;

final readonly class AddCardNotifyRequestCreator implements TransactionCreatorInterface
{
    public function __construct(
        private AddCardRequestFactoryInterface $addCardRequestFactory,
        private AddCardRequestRepositoryInterface $addCardRequestRepository,
        private AddCardPayloadNotifyRequestHashAssignerInterface $addCardPayloadNotifyRequestHashAssigner,
    ) {
    }

    public function create(CreatableTransactionRequest $request): void
    {
        /** @var AddCardRequestInterface $request */

        Assert::isInstanceOf(
            value: $request,
            class: AddCardRequestInterface::class,
            message: 'Request must be instance of %2$s, but is %s.',
        );

        Assert::eq(
            value: $request->getAction(),
            expect: AddCardRequestInterface::ACTION_CAPTURE,
            message: 'Request action must be %2$s, but is %s.',
        );

        $notifyAddCardRequest = $this->addCardRequestFactory->createNotifyRequest(
            captureRequest: $request,
        );

        $this->addCardRequestRepository->add($notifyAddCardRequest);

        $notifyAddCardRequest = $this->addCardRequestRepository->find(
            id: $notifyAddCardRequest->getId(),
        );

        $this->addCardPayloadNotifyRequestHashAssigner->assign(
            request: $request,
            notifyRequestHash: $notifyAddCardRequest->getHash()?->toString(),
        );
    }
}
