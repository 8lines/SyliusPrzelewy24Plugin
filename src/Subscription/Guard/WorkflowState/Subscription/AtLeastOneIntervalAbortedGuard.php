<?php

declare(strict_types=1);

namespace BitBag\SyliusPrzelewy24Plugin\Subscription\Guard\WorkflowState\Subscription;

use BitBag\SyliusPrzelewy24Plugin\Subscription\Entity\SubscriptionInterface;
use Symfony\Component\Workflow\Event\GuardEvent;

final readonly class AtLeastOneIntervalAbortedGuard implements SubscriptionGuardInterface
{
    private const MESSAGE = 'At least one interval must be aborted.';

    public function guardReview(GuardEvent $event): void
    {
        /** @var SubscriptionInterface $subscription */
        $subscription = $event->getSubject();

        foreach ($subscription->getSchedule()->getIntervals() as $interval) {
            if (true === $interval->isAborted()) {
                return;
            }
        }

        $event->setBlocked(
            blocked: true,
            message: self::MESSAGE
        );
    }
}
