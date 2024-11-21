<?php

declare(strict_types=1);

namespace BitBag\SyliusPrzelewy24Plugin\Subscription\Applicator;

use BitBag\SyliusPrzelewy24Plugin\Subscription\Entity\RecurringSyliusOrderInterface;

interface OrderRecurringStateApplicatorInterface
{
    public function apply(RecurringSyliusOrderInterface $order): void;
}
