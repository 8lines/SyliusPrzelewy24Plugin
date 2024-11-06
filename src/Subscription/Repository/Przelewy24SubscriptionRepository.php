<?php

declare(strict_types=1);

namespace BitBag\SyliusPrzelewy24Plugin\Subscription\Repository;

use BitBag\SyliusPrzelewy24Plugin\Subscription\Entity\Przelewy24SubscriptionInterface;
use Sylius\Bundle\ResourceBundle\Doctrine\ORM\EntityRepository;

final class Przelewy24SubscriptionRepository extends EntityRepository implements Przelewy24SubscriptionRepositoryInterface
{
    public function findActiveSubscriptions(): array
    {
        return $this->findBy([
            'state' => Przelewy24SubscriptionInterface::STATE_ACTIVE,
        ]);
    }
}
