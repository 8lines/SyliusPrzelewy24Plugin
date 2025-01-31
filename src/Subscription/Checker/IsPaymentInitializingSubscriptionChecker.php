<?php

declare(strict_types=1);

namespace BitBag\SyliusPrzelewy24Plugin\Subscription\Checker;

use BitBag\SyliusPrzelewy24Plugin\Shared\Entity\TransactionalPaymentRequestInterface;
use BitBag\SyliusPrzelewy24Plugin\Subscription\Entity\RecurringSyliusOrderInterface;
use BitBag\SyliusPrzelewy24Plugin\Subscription\Entity\Subscription;

final readonly class IsPaymentInitializingSubscriptionChecker implements IsPaymentInitializingSubscriptionCheckerInterface
{
    public function isInitializingSubscription(TransactionalPaymentRequestInterface $request): bool
    {
        /** @var RecurringSyliusOrderInterface $order */
        $order = $request->getOrder();

        return Subscription::INITIAL_SEQUENCE === $order->getRecurringPrzelewy24Order()->getRecurringSequenceIndex();
    }
}
