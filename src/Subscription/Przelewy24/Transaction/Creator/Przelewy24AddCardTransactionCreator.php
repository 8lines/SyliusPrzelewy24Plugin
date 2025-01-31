<?php

declare(strict_types=1);

namespace BitBag\SyliusPrzelewy24Plugin\Subscription\Przelewy24\Transaction\Creator;

use BitBag\SyliusPrzelewy24Plugin\Shared\Creator\CreatableTransactionRequest;
use BitBag\SyliusPrzelewy24Plugin\Shared\Creator\TransactionCreatorInterface;
use BitBag\SyliusPrzelewy24Plugin\Shared\Przelewy24\Transaction\Creator\Przelewy24TransactionCreatorTrait;
use Przelewy24\Enums\TransactionChannel;

final readonly class Przelewy24AddCardTransactionCreator implements TransactionCreatorInterface
{
    public const TRANSACTION_DESCRIPTION = 'Kwota próbkowa 1 PLN';

    use Przelewy24TransactionCreatorTrait;

    public function create(CreatableTransactionRequest $request): void
    {
        $this->registerTransaction(
            request: $request,
            description: self::TRANSACTION_DESCRIPTION,
            channel: TransactionChannel::CARDS_ONLY,
        );
    }
}
