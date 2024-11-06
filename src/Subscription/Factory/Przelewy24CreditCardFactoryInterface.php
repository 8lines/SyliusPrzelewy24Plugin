<?php

declare(strict_types=1);

namespace BitBag\SyliusPrzelewy24Plugin\Subscription\Factory;

use BitBag\SyliusPrzelewy24Plugin\Subscription\Entity\Przelewy24CreditCardInterface;
use Sylius\Resource\Factory\FactoryInterface;

/**
 * @extends FactoryInterface<Przelewy24CreditCardInterface>
 */
interface Przelewy24CreditCardFactoryInterface extends FactoryInterface
{
    public function createUsingCardData(
        string $cardMask,
        string $cardDate,
        string $cardRefId,
    ): Przelewy24CreditCardInterface;
}
