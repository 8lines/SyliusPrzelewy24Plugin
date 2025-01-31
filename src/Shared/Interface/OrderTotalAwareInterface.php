<?php

declare(strict_types=1);

namespace BitBag\SyliusPrzelewy24Plugin\Shared\Interface;

interface OrderTotalAwareInterface
{
    public function getOrderTotal(): ?int;

    public function getShippingTotal(): ?int;
}
