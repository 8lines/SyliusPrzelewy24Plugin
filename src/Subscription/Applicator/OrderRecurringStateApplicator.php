<?php

declare(strict_types=1);

namespace BitBag\SyliusPrzelewy24Plugin\Subscription\Applicator;

use BitBag\SyliusPrzelewy24Plugin\Subscription\Entity\RecurringSyliusOrderInterface;
use BitBag\SyliusPrzelewy24Plugin\Subscription\Entity\SubscriptionInterface;

final readonly class OrderRecurringStateApplicator implements OrderRecurringStateApplicatorInterface
{
    public function apply(RecurringSyliusOrderInterface $order): void
    {
        $order->getRecurringPrzelewy24Order()->setRecurring(true);
        $order->getRecurringPrzelewy24Order()->setRecurringSequenceIndex(SubscriptionInterface::INITIAL_SEQUENCE);
    }
}
