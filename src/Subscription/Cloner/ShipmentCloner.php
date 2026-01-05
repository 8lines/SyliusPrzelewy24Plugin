<?php

declare(strict_types=1);

namespace BitBag\SyliusPrzelewy24Plugin\Subscription\Cloner;

use Sylius\Component\Core\Model\ShipmentInterface;
use Sylius\Resource\Factory\FactoryInterface;
use Symfony\Component\Clock\ClockInterface;

final readonly class ShipmentCloner implements ShipmentClonerInterface
{
    public function __construct(
        private FactoryInterface $shipmentFactory,
        private ClockInterface $clock,
    ) {
    }

    public function clone(ShipmentInterface $baseShipment): ShipmentInterface
    {
        /** @var ShipmentInterface $clonedShipment */
        $clonedShipment = $this->shipmentFactory->createNew();

        $clonedShipment->setState(ShipmentInterface::STATE_READY);
        $clonedShipment->setTracking(null);
        $clonedShipment->setShippedAt(null);
        $clonedShipment->setMethod($baseShipment->getMethod());
        $clonedShipment->setCreatedAt($this->clock->now());
        $clonedShipment->setUpdatedAt($this->clock->now());

        return $clonedShipment;
    }
}
