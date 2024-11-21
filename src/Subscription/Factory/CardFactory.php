<?php

declare(strict_types=1);

namespace BitBag\SyliusPrzelewy24Plugin\Subscription\Factory;

use BitBag\SyliusPrzelewy24Plugin\Subscription\Entity\CardInterface;
use Sylius\Resource\Factory\FactoryInterface;
use Sylius\Resource\Generator\RandomnessGeneratorInterface;

final readonly class CardFactory implements CardFactoryInterface
{
    public function __construct(
        private FactoryInterface $decoratedFactory,
        private RandomnessGeneratorInterface $randomnessGenerator,
    ) {
    }

    public function createNew(): CardInterface
    {
        return $this->decoratedFactory->createNew();
    }

    public function createUsingCardData(
        string $mask,
        string $date,
        string $refId,
    ): CardInterface {
        $token = $this->randomnessGenerator->generateUriSafeString(
            length: CardInterface::CARD_TOKEN_LENGTH,
        );

        $card = $this->createNew();

        $card->setToken($token);
        $card->setMask($mask);
        $card->setDate($date);
        $card->setRefId($refId);

        return $card;
    }
}
