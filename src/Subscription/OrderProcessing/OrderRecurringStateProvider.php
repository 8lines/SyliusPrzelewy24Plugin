<?php

declare(strict_types=1);

namespace BitBag\SyliusPrzelewy24Plugin\Subscription\OrderProcessing;

use BitBag\SyliusPrzelewy24Plugin\Subscription\Applicator\OrderRecurringStateApplicatorInterface;
use BitBag\SyliusPrzelewy24Plugin\Subscription\Checker\HasOrderRecurringProductsCheckerInterface;
use BitBag\SyliusPrzelewy24Plugin\Subscription\Entity\RecurringSyliusOrderInterface;
use Sylius\Component\Order\Model\OrderInterface as ModelOrderInterface;
use Sylius\Component\Order\Processor\OrderProcessorInterface;

final readonly class OrderRecurringStateProvider implements OrderProcessorInterface
{
    public function __construct(
        private HasOrderRecurringProductsCheckerInterface $hasOrderRecurringProductsChecker,
        private OrderRecurringStateApplicatorInterface $orderRecurringStateApplicator,
        private OrderRecurringStateApplicatorInterface $orderNotRecurringStateApplicator,
    ) {
    }

    public function process(ModelOrderInterface $order): void
    {
        if (false === $order instanceof RecurringSyliusOrderInterface) {
            return;
        }

        if (true === $this->hasOrderRecurringProductsChecker->hasOrderRecurringProducts($order)) {
            $this->orderRecurringStateApplicator->apply($order);
        } else {
            $this->orderNotRecurringStateApplicator->apply($order);
        }
    }
}
