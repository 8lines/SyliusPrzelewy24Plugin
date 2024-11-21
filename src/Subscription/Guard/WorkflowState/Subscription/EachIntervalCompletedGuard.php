<?php

declare(strict_types=1);

namespace BitBag\SyliusPrzelewy24Plugin\Subscription\Guard\WorkflowState\Subscription;

use BitBag\SyliusPrzelewy24Plugin\Subscription\Entity\SubscriptionInterface;
use Symfony\Component\Workflow\Event\GuardEvent;

final readonly class EachIntervalCompletedGuard implements SubscriptionGuardInterface
{
    private const MESSAGE = 'Each interval must be completed.';

    public function guardReview(GuardEvent $event): void
    {
        /** @var SubscriptionInterface $subscription */
        $subscription = $event->getSubject();

        foreach ($subscription->getSchedule()->getIntervals() as $interval) {
            if (true === $interval->isCompleted()) {
                continue;
            }

            $event->setBlocked(
                blocked: true,
                message: self::MESSAGE,
            );
        }
    }
}
