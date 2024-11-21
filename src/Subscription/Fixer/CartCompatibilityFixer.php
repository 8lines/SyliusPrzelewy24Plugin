<?php

declare(strict_types=1);

namespace BitBag\SyliusPrzelewy24Plugin\Subscription\Fixer;

use BitBag\SyliusPrzelewy24Plugin\Subscription\Entity\RecurringSyliusOrderInterface;
use BitBag\SyliusPrzelewy24Plugin\Subscription\Entity\RecurringSyliusProductVariantInterface;
use BitBag\SyliusPrzelewy24Plugin\Subscription\Guard\Cart\CartCompatibilityGuardInterface;
use Sylius\Component\Core\Model\OrderItemInterface;
use Sylius\Component\Core\Model\OrderItemUnitInterface;

final readonly class CartCompatibilityFixer implements CartCompatibilityFixerInterface
{
    public function __construct(
        private CartCompatibilityGuardInterface $compositeCartCompatibilityGuard,
    ) {
    }

    public function fix(RecurringSyliusOrderInterface $order): void
    {
        if (true === $this->compositeCartCompatibilityGuard->isSatisfiedBy($order)) {
            return;
        }

        /** @var OrderItemInterface|false $orderItem */
        $orderItem = $order->getItems()->last();

        if (false === $orderItem) {
            $order->clearItems();
            return;
        }

        /** @var RecurringSyliusProductVariantInterface|null $productVariant */
        $productVariant = $orderItem->getVariant();

        if (null === $productVariant) {
            $order->removeItem($orderItem);

            $this->repeat($order);
            return;
        }

        if (false === $productVariant->getRecurringPrzelewy24ProductVariant()->isRecurring()) {
            $order->removeItem($orderItem);

            $this->repeat($order);
            return;
        }

        if ($orderItem->getQuantity() > 1) {
            /** @var OrderItemUnitInterface|null $firstUnit */
            $firstUnit = $orderItem->getUnits()->first();

            if (null === $firstUnit) {
                $order->removeItem($orderItem);

                $this->repeat($order);
                return;
            }

            foreach ($orderItem->getUnits() as $unit) {
                $orderItem->removeUnit($unit);
            }

            $orderItem->addUnit($firstUnit);

            $this->repeat($order);
            return;
        }

        $order->removeItem($orderItem);

        $this->repeat($order);
    }

    private function repeat(RecurringSyliusOrderInterface $order): void
    {
        $order->recalculateAdjustmentsTotal();
        $order->recalculateItemsTotal();

        $this->fix($order);
    }
}
