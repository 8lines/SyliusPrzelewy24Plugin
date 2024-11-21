<?php

declare(strict_types=1);

namespace BitBag\SyliusPrzelewy24Plugin\Subscription\Guard\Cart;

use BitBag\SyliusPrzelewy24Plugin\Subscription\Entity\RecurringSyliusOrderInterface;
use BitBag\SyliusPrzelewy24Plugin\Subscription\Entity\RecurringSyliusProductVariantInterface;
use Sylius\Component\Core\Model\OrderItemInterface;

final readonly class RecurringProductOrNotRecurringProductGuard implements CartCompatibilityGuardInterface
{
    public function isSatisfiedBy(RecurringSyliusOrderInterface $order): bool
    {
        $hasRecurringProduct = false;
        $hasNotRecurringProduct = false;

        /** @var OrderItemInterface $item */
        foreach ($order->getItems() as $item) {
            /** @var RecurringSyliusProductVariantInterface|null $productVariant */
            $productVariant = $item->getVariant();

            if (null === $productVariant) {
                return false;
            }

            if (true === $productVariant->getRecurringPrzelewy24ProductVariant()->isRecurring()) {
                $hasRecurringProduct = true;
            } else {
                $hasNotRecurringProduct = true;
            }

            if (true === $hasRecurringProduct && true === $hasNotRecurringProduct) {
                return false;
            }
        }

        return true;
    }
}
