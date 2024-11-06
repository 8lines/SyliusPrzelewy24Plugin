<?php

declare(strict_types=1);

namespace BitBag\SyliusPrzelewy24Plugin\Subscription\CommandHandler;

use BitBag\SyliusPrzelewy24Plugin\Subscription\Command\MarkOrderNotRecurringCommand;
use BitBag\SyliusPrzelewy24Plugin\Subscription\Entity\Przelewy24OrderInterface;
use BitBag\SyliusPrzelewy24Plugin\Subscription\Repository\Przelewy24OrderRepositoryInterface;
use Webmozart\Assert\Assert;

final class MarkOrderNotRecurringHandler
{
    public function __construct(
        private readonly Przelewy24OrderRepositoryInterface $przelewy24OrderRepository,
    ) {
    }

    public function __invoke(MarkOrderNotRecurringCommand $command): void
    {
        /** @var Przelewy24OrderInterface $przelewy24Order */
        $przelewy24Order = $this->przelewy24OrderRepository->find(
            id: $command->przelewy24OrderId(),
        );

        Assert::notNull(
            value: $przelewy24Order,
            message: 'Przelewy24 order with given id does not exist.',
        );

        $przelewy24Order->setRecurring(false);
        $przelewy24Order->setRecurringSequenceIndex(null);

        $this->przelewy24OrderRepository->add($przelewy24Order);
    }
}
