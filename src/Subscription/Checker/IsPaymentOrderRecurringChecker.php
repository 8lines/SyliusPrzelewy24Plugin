<?php

declare(strict_types=1);

namespace BitBag\SyliusPrzelewy24Plugin\Subscription\Checker;

use BitBag\SyliusPrzelewy24Plugin\Shared\Entity\TransactionalPaymentRequestInterface;
use BitBag\SyliusPrzelewy24Plugin\Subscription\Entity\RecurringSyliusOrderInterface;

final readonly class IsPaymentOrderRecurringChecker implements IsPaymentOrderRecurringCheckerInterface
{
    public function isRecurring(TransactionalPaymentRequestInterface $request): bool
    {
        /** @var RecurringSyliusOrderInterface $order */
        $order = $request->getOrder();

        return true === $order->getRecurringPrzelewy24Order()->isRecurring();
    }
}
