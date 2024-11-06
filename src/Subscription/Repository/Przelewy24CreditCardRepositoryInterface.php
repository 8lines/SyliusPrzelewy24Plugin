<?php

declare(strict_types=1);

namespace BitBag\SyliusPrzelewy24Plugin\Subscription\Repository;

use BitBag\SyliusPrzelewy24Plugin\Subscription\Entity\Przelewy24CreditCardInterface;
use Sylius\Resource\Doctrine\Persistence\RepositoryInterface;

/**
 * @extends RepositoryInterface<Przelewy24CreditCardInterface>
 */
interface Przelewy24CreditCardRepositoryInterface extends RepositoryInterface
{
    public function findByCardRefIdAndPrzelewy24CustomerId(
        string $cardRefId,
        int $przelewy24CustomerId,
    ): ?Przelewy24CreditCardInterface;

    public function existsByCardRefIdAndPrzelewy24CustomerId(
        string $cardRefId,
        int $przelewy24CustomerId,
    ): bool;
}
