<?php

declare(strict_types=1);

namespace BitBag\SyliusPrzelewy24Plugin\Subscription\Repository;

use BitBag\SyliusPrzelewy24Plugin\Subscription\Entity\SubscriptionInterface;
use Sylius\Resource\Doctrine\Persistence\RepositoryInterface;

/**
 * @extends RepositoryInterface<SubscriptionInterface>
 */
interface SubscriptionRepositoryInterface extends RepositoryInterface
{
    /**
     * @return SubscriptionInterface[]
     */
    public function findActiveSubscriptions(): array;
}
