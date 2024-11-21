<?php

declare(strict_types=1);

namespace BitBag\SyliusPrzelewy24Plugin\Subscription\Factory;

use BitBag\SyliusPrzelewy24Plugin\Subscription\Entity\CardInterface;
use Sylius\Resource\Factory\FactoryInterface;

/**
 * @extends FactoryInterface<CardInterface>
 */
interface CardFactoryInterface extends FactoryInterface
{
    public function createUsingCardData(
        string $mask,
        string $date,
        string $refId,
    ): CardInterface;
}
