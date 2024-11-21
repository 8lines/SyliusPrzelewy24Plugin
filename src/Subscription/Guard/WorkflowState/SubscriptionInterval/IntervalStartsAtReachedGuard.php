<?php

declare(strict_types=1);

namespace BitBag\SyliusPrzelewy24Plugin\Subscription\Guard\WorkflowState\SubscriptionInterval;

use BitBag\SyliusPrzelewy24Plugin\Subscription\Entity\SubscriptionIntervalInterface;
use Symfony\Component\Clock\ClockInterface;
use Symfony\Component\Workflow\Event\GuardEvent;

final readonly class IntervalStartsAtReachedGuard implements SubscriptionIntervalGuardInterface
{
    private const MESSAGE = 'Interval has not started yet.';

    public function __construct(
        private ClockInterface $clock,
    ) {
    }

    public function guardReview(GuardEvent $event): void
    {
        /** @var SubscriptionIntervalInterface $interval */
        $interval = $event->getSubject();

        if (null === $interval->getStartsAt()) {
            $event->setBlocked(
                blocked: true,
                message: self::MESSAGE
            );
        }

        if (false === $interval->getStartsAt() < $this->clock->now()) {
            $event->setBlocked(
                blocked: true,
                message: self::MESSAGE
            );
        }
    }
}
