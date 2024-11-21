<?php

declare(strict_types=1);

namespace BitBag\SyliusPrzelewy24Plugin\Subscription\Repository;

use BitBag\SyliusPrzelewy24Plugin\Subscription\Entity\CardInterface;
use Sylius\Bundle\ResourceBundle\Doctrine\ORM\EntityRepository;

final class CardRepository extends EntityRepository implements CardRepositoryInterface
{
    public function findByRefIdAndSubscriberId(
        string $refId,
        int $subscriberId,
    ): ?CardInterface {
        return $this->findOneBy([
            'refId' => $refId,
            'owner' => $subscriberId,
        ]);
    }

    public function existsByRefIdAndSubscriberId(
        string $refId,
        int $subscriberId,
    ): bool {
        return null !== $this->findByRefIdAndSubscriberId(
            refId: $refId,
            subscriberId: $subscriberId,
        );
    }

    public function findByTokenAndSubscriberId(
        string $token,
        int $subscriberId,
    ): ?CardInterface {
        return $this->findOneBy([
            'token' => $token,
            'owner' => $subscriberId,
        ]);
    }
}
