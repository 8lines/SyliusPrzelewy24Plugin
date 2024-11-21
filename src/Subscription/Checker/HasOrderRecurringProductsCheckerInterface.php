<?php

declare(strict_types=1);

namespace BitBag\SyliusPrzelewy24Plugin\Subscription\Checker;

use BitBag\SyliusPrzelewy24Plugin\Subscription\Entity\RecurringSyliusOrderInterface;

interface HasOrderRecurringProductsCheckerInterface
{
    public function hasOrderRecurringProducts(RecurringSyliusOrderInterface $order): bool;
}
