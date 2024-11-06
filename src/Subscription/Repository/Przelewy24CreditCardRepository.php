<?php

declare(strict_types=1);

namespace BitBag\SyliusPrzelewy24Plugin\Subscription\Repository;

use BitBag\SyliusPrzelewy24Plugin\Subscription\Entity\Przelewy24CreditCardInterface;
use Sylius\Bundle\ResourceBundle\Doctrine\ORM\EntityRepository;

final class Przelewy24CreditCardRepository extends EntityRepository implements Przelewy24CreditCardRepositoryInterface
{
    public function findByCardRefIdAndPrzelewy24CustomerId(
        string $cardRefId,
        int $przelewy24CustomerId,
    ): ?Przelewy24CreditCardInterface {
        return $this->findOneBy([
            'cardRefId' => $cardRefId,
            'owner' => $przelewy24CustomerId,
        ]);
    }

    public function existsByCardRefIdAndPrzelewy24CustomerId(
        string $cardRefId,
        int $przelewy24CustomerId,
    ): bool {
        return null !== $this->findByCardRefIdAndPrzelewy24CustomerId(
            cardRefId: $cardRefId,
            przelewy24CustomerId: $przelewy24CustomerId,
        );
    }
}
