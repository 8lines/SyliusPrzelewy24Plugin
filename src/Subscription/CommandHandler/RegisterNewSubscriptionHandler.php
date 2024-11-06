<?php

declare(strict_types=1);

namespace BitBag\SyliusPrzelewy24Plugin\Subscription\CommandHandler;

use BitBag\SyliusPrzelewy24Plugin\Subscription\Command\RegisterNewSubscriptionCommand;
use BitBag\SyliusPrzelewy24Plugin\Subscription\Entity\RecurringOrderInterface;
use BitBag\SyliusPrzelewy24Plugin\Subscription\Factory\Przelewy24SubscriptionFactoryInterface;
use BitBag\SyliusPrzelewy24Plugin\Subscription\Repository\Przelewy24SubscriptionRepositoryInterface;
use Sylius\Component\Resource\Repository\RepositoryInterface;
use Webmozart\Assert\Assert;

final class RegisterNewSubscriptionHandler
{
    public function __construct(
        private readonly RepositoryInterface $syliusOrderRepository,
        private readonly Przelewy24SubscriptionFactoryInterface $przelewy24SubscriptionFactory,
        private readonly Przelewy24SubscriptionRepositoryInterface $przelewy24SubscriptionRepository,
    ) {
    }

    public function __invoke(RegisterNewSubscriptionCommand $command): void
    {
        /** @var RecurringOrderInterface|null $recurringOrder */
        $recurringOrder = $this->syliusOrderRepository->find(
            id: $command->syliusRecurringOrderId(),
        );

        Assert::notNull(
            value: $recurringOrder,
            message: 'Recurring order with given id does not exist.',
        );

        $subscription = $this->przelewy24SubscriptionFactory->createFromRecurringOrder(
            recurringOrder: $recurringOrder,
        );

        $this->przelewy24SubscriptionRepository->add($subscription);
    }
}
