<?php

declare(strict_types=1);

namespace BitBag\SyliusPrzelewy24Plugin\Subscription\Cloner;

use Sylius\Component\Core\Model\ShipmentInterface;

interface ShipmentClonerInterface
{
    public function clone(ShipmentInterface $baseShipment): ShipmentInterface;
}
