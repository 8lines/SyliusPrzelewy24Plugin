<?php

declare(strict_types=1);

namespace BitBag\SyliusPrzelewy24Plugin\Subscription\Cloner;

use BitBag\SyliusPrzelewy24Plugin\Subscription\Entity\RecurringOrderInterface;

interface RecurringOrderClonerInterface
{
    public function clone(RecurringOrderInterface $baseRecurringOrder): RecurringOrderInterface;
}
