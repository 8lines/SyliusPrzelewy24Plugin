<?php

declare(strict_types=1);

namespace BitBag\SyliusPrzelewy24Plugin\Subscription\Creator;

use BitBag\SyliusPrzelewy24Plugin\Subscription\Entity\RecurringSyliusOrderInterface;

interface SubscriptionCreatorInterface
{
    public function createFromOrder(RecurringSyliusOrderInterface $order): void;
}
