<?php

declare(strict_types=1);

namespace BitBag\SyliusPrzelewy24Plugin\Subscription\EventListener;

use BitBag\SyliusPrzelewy24Plugin\Subscription\Entity\RecurringOrderInterface;
use Sylius\Bundle\ResourceBundle\Event\ResourceControllerEvent;
use Sylius\Component\Core\Model\OrderItemInterface;
use Sylius\Component\Order\Context\CartContextInterface;

final class AnyProductPreAddToRecurringOrderListener
{
    public function __construct(
        private readonly CartContextInterface $cartContext,
    ) {
    }

    public function preventAddingAnyProductToRecurringOrder(ResourceControllerEvent $event): void
    {
        if (false === $this->supports($event)) {
            return;
        }

        $event->stop('bitbag_sylius_przelewy24_plugin.ui.cannot_add_product_to_recurring_order');
    }

    private function supports(ResourceControllerEvent $event): bool
    {
        if (false === $event->getSubject() instanceof OrderItemInterface) {
            return false;
        }

        if (false === $this->cartContext->getCart() instanceof RecurringOrderInterface) {
            return false;
        }

        /** @var RecurringOrderInterface $recurringOrder */
        $recurringOrder = $this->cartContext->getCart();

        if (false === $recurringOrder->getPrzelewy24Order()->isRecurring()) {
            return false;
        }

        return true;
    }
}
