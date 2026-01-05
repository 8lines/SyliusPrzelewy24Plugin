<?php

declare(strict_types=1);

namespace BitBag\SyliusPrzelewy24Plugin\Subscription\Cloner;

use BitBag\SyliusPrzelewy24Plugin\Subscription\Entity\RecurringSyliusOrderInterface;
use Sylius\Abstraction\StateMachine\StateMachineInterface;
use Sylius\Bundle\OrderBundle\NumberAssigner\OrderNumberAssignerInterface;
use Sylius\Component\Core\Model\AdjustmentInterface;
use Sylius\Component\Core\Model\OrderInterface;
use Sylius\Component\Core\OrderCheckoutStates;
use Sylius\Component\Core\OrderPaymentStates;
use Sylius\Component\Core\OrderShippingStates;
use Sylius\Component\Order\OrderTransitions;
use Sylius\Component\Order\Processor\OrderProcessorInterface;
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
        private OrderProcessorInterface $orderProcessor,
        private StateMachineInterface $stateMachine,
    ) {
    }

    public function clone(RecurringSyliusOrderInterface $baseOrder): RecurringSyliusOrderInterface
    {
        /** @var RecurringSyliusOrderInterface $clonedOrder */
        $clonedOrder = $this->orderFactory->createNew();

        $clonedOrder->setNotes($baseOrder->getNotes());
        $clonedOrder->setChannel($baseOrder->getChannel());
        $clonedOrder->setCustomer($baseOrder->getCustomer());
        $clonedOrder->setCurrencyCode($baseOrder->getCurrencyCode());
        $clonedOrder->setCustomerIp($baseOrder->getCustomerIp());
        $clonedOrder->setLocaleCode($baseOrder->getLocaleCode());
        $clonedOrder->setPromotionCoupon($baseOrder->getPromotionCoupon());
        $clonedOrder->setShippingAddress(clone $baseOrder->getShippingAddress());
        $clonedOrder->setBillingAddress(clone $baseOrder->getBillingAddress());

        foreach ($baseOrder->getItems() as $orderItem) {
            $clonedOrderItem = $this->orderItemCloner->clone($orderItem);
            $clonedOrderItem->setOrder($clonedOrder);

            $clonedOrder->addItem($clonedOrderItem);
        }

        $this->stateMachine->apply(
            subject: $clonedOrder,
            graphName: OrderTransitions::GRAPH,
            transition: OrderTransitions::TRANSITION_CREATE,
        );

        return $clonedOrder;
    }
}
