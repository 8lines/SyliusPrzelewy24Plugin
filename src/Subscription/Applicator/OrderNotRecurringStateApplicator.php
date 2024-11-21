<?php

declare(strict_types=1);

namespace BitBag\SyliusPrzelewy24Plugin\Subscription\Applicator;

use BitBag\SyliusPrzelewy24Plugin\Subscription\Entity\RecurringSyliusOrderInterface;

final readonly class OrderNotRecurringStateApplicator implements OrderRecurringStateApplicatorInterface
{
    public function apply(RecurringSyliusOrderInterface $order): void
    {
        $order->getRecurringPrzelewy24Order()->setRecurring(false);
    }
}
