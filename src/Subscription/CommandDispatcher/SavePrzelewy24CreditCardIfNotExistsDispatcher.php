<?php

declare(strict_types=1);

namespace BitBag\SyliusPrzelewy24Plugin\Subscription\CommandDispatcher;

use BitBag\SyliusPrzelewy24Plugin\Subscription\Command\SavePrzelewy24CreditCardIfNotExistsCommand;
use Symfony\Component\Messenger\MessageBusInterface;

final class SavePrzelewy24CreditCardIfNotExistsDispatcher implements SavePrzelewy24CreditCardIfNotExistsDispatcherInterface
{
    public function __construct(
        private readonly MessageBusInterface $messageBus,
    ) {
    }

    public function dispatch(
        int $przelewy24CustomerId,
        string $cardMask,
        string $cardDate,
        string $cardRefId,
    ): void {
        $this->messageBus->dispatch(new SavePrzelewy24CreditCardIfNotExistsCommand(
            przelewy24CustomerId: $przelewy24CustomerId,
            cardMask: $cardMask,
            cardDate: $cardDate,
            cardRefId: $cardRefId,
        ));
    }
}
