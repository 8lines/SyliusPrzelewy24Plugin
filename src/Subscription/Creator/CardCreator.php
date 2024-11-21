<?php

declare(strict_types=1);

namespace BitBag\SyliusPrzelewy24Plugin\Subscription\Creator;

use BitBag\SyliusPrzelewy24Plugin\Subscription\Entity\SyliusCustomerAsSubscriberInterface;
use BitBag\SyliusPrzelewy24Plugin\Subscription\Factory\CardFactoryInterface;
use BitBag\SyliusPrzelewy24Plugin\Subscription\Repository\CardRepositoryInterface;

final readonly class CardCreator implements CardCreatorInterface
{
    public function __construct(
        private CardRepositoryInterface $cardRepository,
        private CardFactoryInterface $cardFactory,
    ) {
    }

    public function createForCustomerIfNotExists(
        SyliusCustomerAsSubscriberInterface $customer,
        string $refId,
        string $mask,
        string $date,
    ): void {
        $existsByRefId = $this->cardRepository->existsByRefIdAndSubscriberId(
            refId: $refId,
            subscriberId: $customer->getPrzelewy24Subscriber()->getId(),
        );

        if (true === $existsByRefId) {
            return;
        }

        $card = $this->cardFactory->createUsingCardData(
            mask: $mask,
            date: $date,
            refId: $refId,
        );

        $customer->getPrzelewy24Subscriber()->addCard(
            card: $card,
        );

        $this->cardRepository->add($card);
    }
}
