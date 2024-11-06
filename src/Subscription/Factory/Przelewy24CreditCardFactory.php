<?php

declare(strict_types=1);

namespace BitBag\SyliusPrzelewy24Plugin\Subscription\Factory;

use BitBag\SyliusPrzelewy24Plugin\Subscription\Entity\Przelewy24CreditCardInterface;
use Sylius\Resource\Factory\FactoryInterface;

final class Przelewy24CreditCardFactory implements Przelewy24CreditCardFactoryInterface
{
    public function __construct(
        private readonly FactoryInterface $decoratedFactory,
    ) {
    }

    public function createNew(): Przelewy24CreditCardInterface
    {
        return $this->decoratedFactory->createNew();
    }

    public function createUsingCardData(
        string $cardMask,
        string $cardDate,
        string $cardRefId,
    ): Przelewy24CreditCardInterface {
        $creditCard = $this->createNew();

        $creditCard->setCardMask($cardMask);
        $creditCard->setCardDate($cardDate);
        $creditCard->setCardRefId($cardRefId);

        return $creditCard;
    }
}
