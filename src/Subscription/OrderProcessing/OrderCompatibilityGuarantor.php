<?php

declare(strict_types=1);

namespace BitBag\SyliusPrzelewy24Plugin\Subscription\OrderProcessing;

use BitBag\SyliusPrzelewy24Plugin\Subscription\Entity\RecurringSyliusOrderInterface;
use BitBag\SyliusPrzelewy24Plugin\Subscription\Fixer\CartCompatibilityFixerInterface;
use BitBag\SyliusPrzelewy24Plugin\Subscription\Guard\Cart\CartCompatibilityGuardInterface;
use Sylius\Component\Order\Model\OrderInterface as ModelOrderInterface;
use Sylius\Component\Order\Processor\OrderProcessorInterface;

final readonly class OrderCompatibilityGuarantor implements OrderProcessorInterface
{
    public function __construct(
        private CartCompatibilityGuardInterface $compositeCartCompatibilityGuard,
        private CartCompatibilityFixerInterface $cartCompatibilityFixer,
    ) {
    }

    public function process(ModelOrderInterface $order): void
    {
        if (false === $order instanceof RecurringSyliusOrderInterface) {
            return;
        }

        if (true === $this->compositeCartCompatibilityGuard->isSatisfiedBy($order)) {
            return;
        }

        $this->cartCompatibilityFixer->fix($order);
    }
}
