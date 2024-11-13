<?php

declare(strict_types=1);

namespace BitBag\SyliusPrzelewy24Plugin\Subscription\Cloner;

use BitBag\SyliusPrzelewy24Plugin\Subscription\Entity\RecurringOrderInterface;
use Sylius\Bundle\OrderBundle\NumberAssigner\OrderNumberAssignerInterface;
use Sylius\Component\Core\Model\AdjustmentInterface;
use Sylius\Component\Core\OrderCheckoutStates;
use Sylius\Component\Core\OrderPaymentStates;
use Sylius\Component\Core\OrderShippingStates;
use Sylius\Resource\Factory\FactoryInterface;
use Sylius\Resource\Generator\RandomnessGeneratorInterface;
use Symfony\Component\Clock\ClockInterface;

final class RecurringOrderCloner implements RecurringOrderClonerInterface
{
    public function __construct(
        private readonly FactoryInterface $recurringOrderFactory,
        private readonly OrderNumberAssignerInterface $orderNumberAssigner,
        private readonly ClockInterface $clock,
        private readonly RandomnessGeneratorInterface $randomnessGenerator,
        private readonly OrderItemClonerInterface $orderItemCloner,
        private readonly AdjustmentClonerInterface $adjustmentCloner,
        private readonly ShipmentClonerInterface $shipmentCloner,
    ) {
    }

    public function clone(RecurringOrderInterface $baseRecurringOrder): RecurringOrderInterface
    {
        /** @var RecurringOrderInterface $clonedRecurringOrder */
        $clonedRecurringOrder = $this->recurringOrderFactory->createNew();

        $clonedRecurringOrder->setState(RecurringOrderInterface::STATE_NEW);
        $clonedRecurringOrder->setNotes($baseRecurringOrder->getNotes());
        $clonedRecurringOrder->setChannel($baseRecurringOrder->getChannel());
        $clonedRecurringOrder->setCheckoutState(OrderCheckoutStates::STATE_COMPLETED);
        $clonedRecurringOrder->setCustomer($baseRecurringOrder->getCustomer());
        $clonedRecurringOrder->setCurrencyCode($baseRecurringOrder->getCurrencyCode());
        $clonedRecurringOrder->setCustomerIp($baseRecurringOrder->getCustomerIp());
        $clonedRecurringOrder->setLocaleCode($baseRecurringOrder->getLocaleCode());
        $clonedRecurringOrder->setPaymentState(OrderPaymentStates::STATE_AWAITING_PAYMENT);
        $clonedRecurringOrder->setPromotionCoupon($baseRecurringOrder->getPromotionCoupon());
        $clonedRecurringOrder->setShippingAddress(clone $baseRecurringOrder->getShippingAddress());
        $clonedRecurringOrder->setBillingAddress(clone $baseRecurringOrder->getBillingAddress());
        $clonedRecurringOrder->setShippingState(OrderShippingStates::STATE_READY);
        $clonedRecurringOrder->setTokenValue($this->randomnessGenerator->generateUriSafeString(10));
        $clonedRecurringOrder->setCreatedAt($this->clock->now());
        $clonedRecurringOrder->setUpdatedAt($this->clock->now());
        $clonedRecurringOrder->setCheckoutCompletedAt($this->clock->now());

        $this->orderNumberAssigner->assignNumber($clonedRecurringOrder);

        foreach ($baseRecurringOrder->getItems() as $orderItem) {
            $clonedOrderItem = $this->orderItemCloner->clone($orderItem);
            $clonedOrderItem->setOrder($clonedRecurringOrder);

            $clonedRecurringOrder->addItem($clonedOrderItem);
        }

        foreach ($baseRecurringOrder->getAdjustments() as $adjustment) {
            if (AdjustmentInterface::SHIPPING_ADJUSTMENT === $adjustment->getType()) {
                continue;
            }

            $clonedAdjustment = $this->adjustmentCloner->clone($adjustment);
            $clonedRecurringOrder->addAdjustment($clonedAdjustment);
        }

        if (true === $clonedRecurringOrder->isShippingRequired()) {
            foreach ($baseRecurringOrder->getShipments() as $shipment) {
                $clonedShipment = $this->shipmentCloner->clone($shipment);
                $clonedShipment->setOrder($clonedRecurringOrder);

                $clonedRecurringOrder->addShipment($clonedShipment);

                foreach ($clonedRecurringOrder->getItemUnits() as $itemUnit) {
                    $clonedShipment->addUnit($itemUnit);
                }

                foreach ($shipment->getAdjustments() as $adjustment) {
                    $clonedAdjustment = $this->adjustmentCloner->clone($adjustment);

                    $clonedAdjustment->setShipment($clonedShipment);
                    $clonedAdjustment->setAdjustable($clonedRecurringOrder);

                    $clonedShipment->addAdjustment($clonedAdjustment);
                }
            }
        }

        $clonedRecurringOrder->recalculateAdjustmentsTotal();
        $clonedRecurringOrder->recalculateItemsTotal();

        return $clonedRecurringOrder;
    }
}
