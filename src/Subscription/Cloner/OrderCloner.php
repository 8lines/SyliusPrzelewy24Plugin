<?php

declare(strict_types=1);

namespace BitBag\SyliusPrzelewy24Plugin\Subscription\Cloner;

use BitBag\SyliusPrzelewy24Plugin\Subscription\Entity\RecurringSyliusOrderInterface;
use Sylius\Bundle\OrderBundle\NumberAssigner\OrderNumberAssignerInterface;
use Sylius\Component\Core\Model\AdjustmentInterface;
use Sylius\Component\Core\OrderCheckoutStates;
use Sylius\Component\Core\OrderPaymentStates;
use Sylius\Component\Core\OrderShippingStates;
use Sylius\Resource\Factory\FactoryInterface;
use Sylius\Resource\Generator\RandomnessGeneratorInterface;
use Symfony\Component\Clock\ClockInterface;

final readonly class OrderCloner implements OrderClonerInterface
{
    public function __construct(
        private FactoryInterface $orderFactory,
        private OrderNumberAssignerInterface $orderNumberAssigner,
        private ClockInterface $clock,
        private RandomnessGeneratorInterface $randomnessGenerator,
        private OrderItemClonerInterface $orderItemCloner,
        private AdjustmentClonerInterface $adjustmentCloner,
        private ShipmentClonerInterface $shipmentCloner,
    ) {
    }

    public function clone(RecurringSyliusOrderInterface $baseOrder): RecurringSyliusOrderInterface
    {
        /** @var RecurringSyliusOrderInterface $clonedOrder */
        $clonedOrder = $this->orderFactory->createNew();

        $clonedOrder->setState(RecurringSyliusOrderInterface::STATE_NEW);
        $clonedOrder->setNotes($baseOrder->getNotes());
        $clonedOrder->setChannel($baseOrder->getChannel());
        $clonedOrder->setCheckoutState(OrderCheckoutStates::STATE_COMPLETED);
        $clonedOrder->setCustomer($baseOrder->getCustomer());
        $clonedOrder->setCurrencyCode($baseOrder->getCurrencyCode());
        $clonedOrder->setCustomerIp($baseOrder->getCustomerIp());
        $clonedOrder->setLocaleCode($baseOrder->getLocaleCode());
        $clonedOrder->setPaymentState(OrderPaymentStates::STATE_AWAITING_PAYMENT);
        $clonedOrder->setShippingState(OrderShippingStates::STATE_SHIPPED);
        $clonedOrder->setPromotionCoupon($baseOrder->getPromotionCoupon());
        $clonedOrder->setShippingAddress(clone $baseOrder->getShippingAddress());
        $clonedOrder->setBillingAddress(clone $baseOrder->getBillingAddress());
        $clonedOrder->setTokenValue($this->randomnessGenerator->generateUriSafeString(10));
        $clonedOrder->setCreatedAt($this->clock->now());
        $clonedOrder->setUpdatedAt($this->clock->now());
        $clonedOrder->setCheckoutCompletedAt($this->clock->now());

        $this->orderNumberAssigner->assignNumber($clonedOrder);

        foreach ($baseOrder->getItems() as $orderItem) {
            $clonedOrderItem = $this->orderItemCloner->clone($orderItem);
            $clonedOrderItem->setOrder($clonedOrder);

            $clonedOrder->addItem($clonedOrderItem);
        }

        foreach ($baseOrder->getAdjustments() as $adjustment) {
            if (AdjustmentInterface::SHIPPING_ADJUSTMENT === $adjustment->getType()) {
                continue;
            }

            $clonedAdjustment = $this->adjustmentCloner->clone($adjustment);
            $clonedOrder->addAdjustment($clonedAdjustment);
        }

        if (true === $clonedOrder->isShippingRequired()) {
            foreach ($baseOrder->getShipments() as $shipment) {
                $clonedShipment = $this->shipmentCloner->clone($shipment);
                $clonedShipment->setOrder($clonedOrder);

                $clonedOrder->addShipment($clonedShipment);

                foreach ($clonedOrder->getItemUnits() as $itemUnit) {
                    $clonedShipment->addUnit($itemUnit);
                }

                foreach ($shipment->getAdjustments() as $adjustment) {
                    $clonedAdjustment = $this->adjustmentCloner->clone($adjustment);

                    $clonedAdjustment->setShipment($clonedShipment);
                    $clonedAdjustment->setAdjustable($clonedOrder);

                    $clonedShipment->addAdjustment($clonedAdjustment);
                }
            }

            if (true === $clonedOrder->hasShipments()) {
                $clonedOrder->setShippingState(OrderShippingStates::STATE_READY);
            }
        }

        $clonedOrder->recalculateAdjustmentsTotal();
        $clonedOrder->recalculateItemsTotal();

        return $clonedOrder;
    }
}
