<?php

declare(strict_types=1);

namespace BitBag\SyliusPrzelewy24Plugin\Subscription\StateGuard;

use BitBag\SyliusPrzelewy24Plugin\Subscription\Entity\Przelewy24SubscriptionScheduleIntervalInterface;
use Sylius\Component\Core\Model\PaymentInterface;

final class IsPrzelewy24SubscriptionScheduleIntervalPaidGuard implements Przelewy24SubscriptionScheduleIntervalGuardInterface
{
    public function isEligible(Przelewy24SubscriptionScheduleIntervalInterface $interval): bool
    {
        return true === $interval->isPaid();
    }
}
