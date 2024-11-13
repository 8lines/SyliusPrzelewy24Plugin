<?php

declare(strict_types=1);

namespace BitBag\SyliusPrzelewy24Plugin\Subscription\StateGuard;

use BitBag\SyliusPrzelewy24Plugin\Subscription\Entity\Przelewy24SubscriptionScheduleIntervalInterface;
use Sylius\Component\Core\Model\PaymentInterface;

final class HasNotPrzelewy24SubscriptionScheduleIntervalActivePaymentGuard implements Przelewy24SubscriptionScheduleIntervalGuardInterface
{
    public function isEligible(Przelewy24SubscriptionScheduleIntervalInterface $interval): bool
    {
        $lastPayment = $interval->getOrder()?->getSyliusOrder()?->getLastPayment();

        return null === $lastPayment
            || PaymentInterface::STATE_NEW === $lastPayment->getState()
            || PaymentInterface::STATE_FAILED === $lastPayment->getState()
            || PaymentInterface::STATE_CANCELLED === $lastPayment->getState();
    }
}
