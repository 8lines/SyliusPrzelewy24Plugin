<?php

declare(strict_types=1);

namespace BitBag\SyliusPrzelewy24Plugin\Subscription\Guard\Cart;

use BitBag\SyliusPrzelewy24Plugin\Subscription\Entity\RecurringSyliusOrderInterface;

interface CartCompatibilityGuardInterface
{
    public function isSatisfiedBy(RecurringSyliusOrderInterface $order): bool;
}
