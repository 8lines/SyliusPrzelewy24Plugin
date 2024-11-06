<?php

declare(strict_types=1);

namespace BitBag\SyliusPrzelewy24Plugin\Subscription\CommandDispatcher;

use BitBag\SyliusPrzelewy24Plugin\Subscription\Command\RegisterNewSubscriptionCommand;
use Symfony\Component\Messenger\MessageBusInterface;

final class RegisterNewSubscriptionDispatcher implements RegisterNewSubscriptionDispatcherInterface
{
    public function __construct(
        private readonly MessageBusInterface $messageBus,
    ) {
    }

    public function dispatch(int $syliusRecurringOrderId): void
    {
        $this->messageBus->dispatch(new RegisterNewSubscriptionCommand(
            syliusRecurringOrderId: $syliusRecurringOrderId,
        ));
    }
}
