<?php

declare(strict_types=1);

namespace BitBag\SyliusPrzelewy24Plugin\Subscription\EventListener;

use BitBag\SyliusPrzelewy24Plugin\Subscription\Command\MarkOrderNotRecurringCommand;
use BitBag\SyliusPrzelewy24Plugin\Subscription\Entity\RecurringOrderInterface;
use BitBag\SyliusPrzelewy24Plugin\Subscription\Entity\RecurringProductVariantInterface;
use Sylius\Bundle\ResourceBundle\Event\ResourceControllerEvent;
use Sylius\Component\Core\Model\OrderItemInterface;
use Sylius\Component\Order\Context\CartContextInterface;
use Symfony\Component\Messenger\MessageBusInterface;

final class RecurringProductPostRemoveFromRecurringOrderListener
{
    public function __construct(
        private readonly MessageBusInterface $messageBus,
        private readonly CartContextInterface $cartContext,
    ) {
    }

    public function markOrderAsNotRecurringIfRemovedProductIsRecurring(ResourceControllerEvent $event): void
    {
        if (false === $this->supports($event)) {
            return;
        }

        /** @var RecurringOrderInterface $recurringOrder */
        $recurringOrder = $this->cartContext->getCart();

        $this->messageBus->dispatch(new MarkOrderNotRecurringCommand(
            przelewy24OrderId: $recurringOrder->getPrzelewy24Order()->getId(),
        ));
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

        if (false === $recurringOrder->getPrzelewy24Order()->isRecurring()) {
            return false;
        }

        return true;
    }
}
