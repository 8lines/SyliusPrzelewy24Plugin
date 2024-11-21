<?php

declare(strict_types=1);

namespace BitBag\SyliusPrzelewy24Plugin\Subscription\Fixer;

use BitBag\SyliusPrzelewy24Plugin\Subscription\Entity\RecurringSyliusOrderInterface;

interface CartCompatibilityFixerInterface
{
    public function fix(RecurringSyliusOrderInterface $order): void;
}
