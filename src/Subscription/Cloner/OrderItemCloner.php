<?php

declare(strict_types=1);

namespace BitBag\SyliusPrzelewy24Plugin\Subscription\Cloner;

use Sylius\Component\Core\Model\OrderItem;
use Sylius\Component\Core\Model\OrderItemInterface;
use Sylius\Component\Order\Factory\OrderItemUnitFactoryInterface;
use Sylius\Resource\Factory\FactoryInterface;

final readonly class OrderItemCloner implements OrderItemClonerInterface
{
    public function __construct(
        private FactoryInterface $orderItemFactory,
        private OrderItemUnitFactoryInterface $orderItemUnitFactory,
        private AdjustmentClonerInterface $adjustmentCloner,
    ) {
    }

    public function clone(OrderItemInterface $baseOrderItem): OrderItemInterface
    {
        /** @var OrderItem $clonedOrderItem */
        $clonedOrderItem = $this->orderItemFactory->createNew();

        $clonedOrderItem->setProductName($baseOrderItem->getProductName());
        $clonedOrderItem->setUnitPrice($baseOrderItem->getUnitPrice());
        $clonedOrderItem->setOriginalUnitPrice($baseOrderItem->getOriginalUnitPrice());
        $clonedOrderItem->setVariant($baseOrderItem->getVariant());
        $clonedOrderItem->setVariantName($baseOrderItem->getVariantName());
        $clonedOrderItem->setVersion($baseOrderItem->getVersion());
        $clonedOrderItem->setImmutable(true);

        foreach ($baseOrderItem->getAdjustments() as $adjustment) {
            $clonedAdjustment = $this->adjustmentCloner->clone($adjustment);
            $clonedAdjustment->setAdjustable($clonedOrderItem);

            $clonedOrderItem->addAdjustment($clonedAdjustment);
            $clonedOrderItem->recalculateAdjustmentsTotal();
        }

        foreach ($baseOrderItem->getUnits() as $unit) {
            $clonedUnit = $this->orderItemUnitFactory->createForItem($clonedOrderItem);

            foreach ($unit->getAdjustments() as $adjustment) {
                $clonedAdjustment = $this->adjustmentCloner->clone($adjustment);
                $clonedAdjustment->setAdjustable($clonedUnit);

                $clonedUnit->addAdjustment($clonedAdjustment);
                $clonedUnit->recalculateAdjustmentsTotal();
            }

            $clonedOrderItem->addUnit($clonedUnit);
            $clonedOrderItem->recalculateUnitsTotal();
        }

        return $clonedOrderItem;
    }
}
