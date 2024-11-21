<?php

declare(strict_types=1);

namespace BitBag\SyliusPrzelewy24Plugin\Subscription\Cloner;

use BitBag\SyliusPrzelewy24Plugin\Subscription\Entity\RecurringSyliusOrderInterface;

interface OrderClonerInterface
{
    public function clone(RecurringSyliusOrderInterface $baseOrder): RecurringSyliusOrderInterface;
}
