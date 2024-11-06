<?php

declare(strict_types=1);

namespace BitBag\SyliusPrzelewy24Plugin\Subscription\EventListener;

use BitBag\SyliusPrzelewy24Plugin\Subscription\Entity\RecurringOrderInterface;
use BitBag\SyliusPrzelewy24Plugin\Subscription\Entity\RecurringProductVariantInterface;
use Sylius\Bundle\ResourceBundle\Event\ResourceControllerEvent;
use Sylius\Component\Core\Model\OrderItemInterface;
use Sylius\Component\Order\Context\CartContextInterface;

final class RecurringProductPreAddToNotRecurringOrderListener
{
    public function __construct(
        private readonly CartContextInterface $cartContext,
    ) {
    }

    public function preventAddingRecurringProductToNotRecurringOrderIfOrderHasAnyProducts(
        ResourceControllerEvent $event,
    ): void {
        if (false === $this->supports($event)) {
            return;
        }

        /** @var RecurringOrderInterface $recurringOrder */
        $recurringOrder = $this->cartContext->getCart();

        if (true === $recurringOrder->getItems()->isEmpty()) {
            return;
        }

        $event->stop('bitbag_sylius_przelewy24_plugin.ui.cannot_add_recurring_product_to_not_recurring_order_with_products');
    }

    public function preventAddingMoreThanOneRecurringProductToNotRecurringOrder(
        ResourceControllerEvent $event,
    ): void {
        if (false === $this->supports($event)) {
            return;
        }

        /** @var OrderItemInterface $orderItem */
        $orderItem = $event->getSubject();

        if ($orderItem->getQuantity() <= 1) {
            return;
        }

        $event->stop('bitbag_sylius_przelewy24_plugin.ui.cannot_add_more_than_one_recurring_product');
    }

    private function supports(ResourceControllerEvent $event): bool
    {
        if (false === $event->getSubject() instanceof OrderItemInterface) {
            return false;
        }

        /** @var OrderItemInterface $orderItem */
        $orderItem = $event->getSubject();

        if (false === $orderItem->getVariant() instanceof RecurringProductVariantInterface) {
            return false;
        }

        /** @var RecurringProductVariantInterface $recurringProduct */
        $recurringProduct = $orderItem->getVariant();

        if (false === $recurringProduct->getPrzelewy24ProductVariant()->isRecurring()) {
            return false;
        }

        if (false === $this->cartContext->getCart() instanceof RecurringOrderInterface) {
            return false;
        }

        /** @var RecurringOrderInterface $recurringOrder */
        $recurringOrder = $this->cartContext->getCart();

        if (true === $recurringOrder->getPrzelewy24Order()->isRecurring()) {
            return false;
        }

        return true;
    }
}
