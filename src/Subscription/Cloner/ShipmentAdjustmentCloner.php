<?php

declare(strict_types=1);

namespace BitBag\SyliusPrzelewy24Plugin\Subscription\Cloner;

use Sylius\Component\Core\Model\AdjustmentInterface;

final class ShipmentAdjustmentCloner implements AdjustmentClonerInterface
{
    public function __construct(
        private readonly AdjustmentClonerInterface $defaultAdjustmentCloner,
    ) {
    }

    public function clone(AdjustmentInterface $baseAdjustment): AdjustmentInterface
    {
        $clonedAdjustment = $this->defaultAdjustmentCloner->clone($baseAdjustment);

        $clonedAdjustment->setShipment($baseAdjustment->getShipment());
        $clonedAdjustment->setAdjustable($baseAdjustment->getAdjustable());

        return $clonedAdjustment;
    }
}
