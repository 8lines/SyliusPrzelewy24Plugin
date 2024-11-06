<?php

declare(strict_types=1);

namespace BitBag\SyliusPrzelewy24Plugin\Subscription\Repository;

use BitBag\SyliusPrzelewy24Plugin\Subscription\Entity\Przelewy24SubscriptionInterface;
use Sylius\Resource\Doctrine\Persistence\RepositoryInterface;

/**
 * @extends RepositoryInterface<Przelewy24SubscriptionInterface>
 */
interface Przelewy24SubscriptionRepositoryInterface extends RepositoryInterface
{
    /**
     * @return Przelewy24SubscriptionInterface[]
     */
    public function findActiveSubscriptions(): array;
}
