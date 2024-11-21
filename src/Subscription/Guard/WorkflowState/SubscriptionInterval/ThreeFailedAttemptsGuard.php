<?php

declare(strict_types=1);

namespace BitBag\SyliusPrzelewy24Plugin\Subscription\Guard\WorkflowState\SubscriptionInterval;

use BitBag\SyliusPrzelewy24Plugin\Subscription\Entity\SubscriptionIntervalInterface;
use Symfony\Component\Workflow\Event\GuardEvent;

final readonly class ThreeFailedAttemptsGuard implements SubscriptionIntervalGuardInterface
{
    private const MESSAGE = 'Interval has not three failed attempts yet.';

    public function guardReview(GuardEvent $event): void
    {
        /** @var SubscriptionIntervalInterface $interval */
        $interval = $event->getSubject();

        if (false === $interval->getFailedPaymentAttempts() >= 3) {
            $event->setBlocked(
                blocked: true,
                message: self::MESSAGE
            );
        }
    }
}
