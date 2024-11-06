<?php

declare(strict_types=1);

namespace BitBag\SyliusPrzelewy24Plugin\Subscription\EventListener;

use BitBag\SyliusPrzelewy24Plugin\Subscription\Entity\RecurringOrderInterface;
use Sylius\Bundle\ResourceBundle\Event\ResourceControllerEvent;

final class RecurringOrderPreUpdateListener
{
    public function preventOrderUpdateIfOrderIsRecurringAndChangedTotalQuantity(ResourceControllerEvent $event): void
    {
        if (false === $this->supports($event)) {
            return;
        }

        /** @var RecurringOrderInterface $recurringOrder */
        $recurringOrder = $event->getSubject();

        if ($recurringOrder->getTotalQuantity() === 1) {
            return;
        }

        $event->stop('bitbag_sylius_przelewy24_plugin.ui.cannot_update_recurring_order_product_quantity');
    }

    public function supports(ResourceControllerEvent $event): bool
    {
        if (false === $event->getSubject() instanceof RecurringOrderInterface) {
            return false;
        }

        /** @var RecurringOrderInterface $recurringOrder */
        $recurringOrder = $event->getSubject();

        if (false === $recurringOrder->getPrzelewy24Order()->isRecurring()) {
            return false;
        }

        return true;
    }
}
