<?php

declare(strict_types=1);

namespace BitBag\SyliusPrzelewy24Plugin\Subscription\Repository;

use BitBag\SyliusPrzelewy24Plugin\Subscription\Entity\SubscriptionInterface;
use Sylius\Bundle\ResourceBundle\Doctrine\ORM\EntityRepository;

final class SubscriptionRepository extends EntityRepository implements SubscriptionRepositoryInterface
{
    public function findActiveSubscriptions(): array
    {
        return $this->findBy([
            'state' => SubscriptionInterface::STATE_ACTIVE,
        ]);
    }
}
