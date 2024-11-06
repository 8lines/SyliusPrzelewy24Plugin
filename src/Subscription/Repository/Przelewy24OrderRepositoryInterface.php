<?php

declare(strict_types=1);

namespace BitBag\SyliusPrzelewy24Plugin\Subscription\Repository;

use BitBag\SyliusPrzelewy24Plugin\Subscription\Entity\Przelewy24OrderInterface;
use Sylius\Resource\Doctrine\Persistence\RepositoryInterface;

/**
 * @extends RepositoryInterface<Przelewy24OrderInterface>
 */
interface Przelewy24OrderRepositoryInterface extends RepositoryInterface
{
}
