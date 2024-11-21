<?php

declare(strict_types=1);

namespace BitBag\SyliusPrzelewy24Plugin\Subscription\Repository;

use BitBag\SyliusPrzelewy24Plugin\Subscription\Entity\CardInterface;
use Sylius\Resource\Doctrine\Persistence\RepositoryInterface;

/**
 * @extends RepositoryInterface<CardInterface>
 */
interface CardRepositoryInterface extends RepositoryInterface
{
    public function findByRefIdAndSubscriberId(
        string $refId,
        int $subscriberId,
    ): ?CardInterface;

    public function existsByRefIdAndSubscriberId(
        string $refId,
        int $subscriberId,
    ): bool;

    public function findByTokenAndSubscriberId(
        string $token,
        int $subscriberId,
    ): ?CardInterface;
}
