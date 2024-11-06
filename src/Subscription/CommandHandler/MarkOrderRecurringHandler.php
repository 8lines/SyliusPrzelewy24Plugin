<?php

declare(strict_types=1);

namespace BitBag\SyliusPrzelewy24Plugin\Subscription\CommandHandler;

use BitBag\SyliusPrzelewy24Plugin\Subscription\Command\MarkOrderRecurringCommand;
use BitBag\SyliusPrzelewy24Plugin\Subscription\Entity\Przelewy24OrderInterface;
use BitBag\SyliusPrzelewy24Plugin\Subscription\Entity\Przelewy24Subscription;
use BitBag\SyliusPrzelewy24Plugin\Subscription\Repository\Przelewy24OrderRepositoryInterface;
use Webmozart\Assert\Assert;

final class MarkOrderRecurringHandler
{
    public function __construct(
        private readonly Przelewy24OrderRepositoryInterface $przelewy24OrderRepository,
    ) {
    }

    public function __invoke(MarkOrderRecurringCommand $command): void
    {
        /** @var Przelewy24OrderInterface $przelewy24Order */
        $przelewy24Order = $this->przelewy24OrderRepository->find(
            id: $command->przelewy24OrderId(),
        );

        Assert::notNull(
            value: $przelewy24Order,
            message: 'Przelewy24 order with given id does not exist.',
        );

        $przelewy24Order->setRecurring(true);
        $przelewy24Order->setRecurringSequenceIndex(Przelewy24Subscription::INITIAL_SEQUENCE);

        $this->przelewy24OrderRepository->add($przelewy24Order);
    }
}
