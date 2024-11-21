<?php

declare(strict_types=1);

namespace BitBag\SyliusPrzelewy24Plugin\Subscription\Guard\Cart;

use BitBag\SyliusPrzelewy24Plugin\Subscription\Entity\RecurringSyliusOrderInterface;
use Laminas\Stdlib\PriorityQueue;

final readonly class CompositeCartCompatibilityGuard implements CartCompatibilityGuardInterface
{
    /**
     * @var PriorityQueue<CartCompatibilityGuardInterface>
     */
    private PriorityQueue $cartCompatibilityGuards;

    public function __construct()
    {
        $this->cartCompatibilityGuards = new PriorityQueue();
    }

    public function addGuard(
        CartCompatibilityGuardInterface $cartCompatibilityGuard,
        int $priority,
    ): void {
        $this->cartCompatibilityGuards->insert(
            data: $cartCompatibilityGuard,
            priority: $priority,
        );
    }

    public function isSatisfiedBy(RecurringSyliusOrderInterface $order): bool
    {
        /** @var CartCompatibilityGuardInterface $cartCompatibilityGuard */
        foreach ($this->cartCompatibilityGuards as $cartCompatibilityGuard) {
            if (false === $cartCompatibilityGuard->isSatisfiedBy($order)) {
                return false;
            }
        }

        return true;
    }
}
