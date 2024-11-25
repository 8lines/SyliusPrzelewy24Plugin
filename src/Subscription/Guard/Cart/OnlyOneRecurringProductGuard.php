<?php

declare(strict_types=1);

namespace BitBag\SyliusPrzelewy24Plugin\Subscription\Guard\Cart;

use BitBag\SyliusPrzelewy24Plugin\Subscription\Entity\RecurringSyliusOrderInterface;
use BitBag\SyliusPrzelewy24Plugin\Subscription\Entity\RecurringSyliusProductVariantInterface;
use Sylius\Component\Core\Model\OrderItemInterface;

final readonly class OnlyOneRecurringProductGuard implements CartCompatibilityGuardInterface
{
    public function isSatisfiedBy(RecurringSyliusOrderInterface $order): bool
    {
        $hasRecurringProduct = false;

        /** @var OrderItemInterface $item */
        foreach ($order->getItems() as $item) {
            /** @var RecurringSyliusProductVariantInterface|null $productVariant */
            $productVariant = $item->getVariant();

            if (null === $productVariant) {
                return false;
            }

            if (false === $productVariant->getRecurringPrzelewy24ProductVariant()->isRecurring()) {
                continue;
            }

            if (true === $hasRecurringProduct) {
                return false;
            }

            $hasRecurringProduct = true;
        }

        return true;
    }
}
