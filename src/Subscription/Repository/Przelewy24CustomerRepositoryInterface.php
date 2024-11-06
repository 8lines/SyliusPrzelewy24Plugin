<?php

declare(strict_types=1);

namespace BitBag\SyliusPrzelewy24Plugin\Subscription\Repository;

use BitBag\SyliusPrzelewy24Plugin\Subscription\Entity\Przelewy24CustomerInterface;
use Sylius\Resource\Doctrine\Persistence\RepositoryInterface;

/**
 * @extends RepositoryInterface<Przelewy24CustomerInterface>
 */
interface Przelewy24CustomerRepositoryInterface extends RepositoryInterface
{
}
