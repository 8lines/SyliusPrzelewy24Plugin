<?php

declare(strict_types=1);

namespace BitBag\SyliusPrzelewy24Plugin\Subscription\Cloner;

use Sylius\Component\Core\Model\OrderItemInterface;

interface OrderItemClonerInterface
{
    public function clone(OrderItemInterface $baseOrderItem): OrderItemInterface;
}
