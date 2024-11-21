<?php

declare(strict_types=1);

namespace BitBag\SyliusPrzelewy24Plugin\Subscription\Guard\WorkflowState\SubscriptionInterval;

use BitBag\SyliusPrzelewy24Plugin\Subscription\Entity\SubscriptionIntervalInterface;
use Sylius\Component\Core\Model\PaymentInterface;
use Symfony\Component\Workflow\Event\GuardEvent;

final readonly class NoActivePaymentGuard implements SubscriptionIntervalGuardInterface
{
    private const MESSAGE = 'There is an active payment for this interval.';

    public function guardReview(GuardEvent $event): void
    {
        /** @var SubscriptionIntervalInterface $interval */
        $interval = $event->getSubject();

        /** @var PaymentInterface|null $lastPayment */
        $lastPayment = $interval->getOrder()?->getSyliusOrder()?->getLastPayment();

        if (null === $lastPayment) {
            return;
        }

        if (PaymentInterface::STATE_NEW === $lastPayment->getState()
            || PaymentInterface::STATE_FAILED === $lastPayment->getState()
            || PaymentInterface::STATE_CANCELLED === $lastPayment->getState()
        ) {
            return;
        }

        $event->setBlocked(
            blocked: true,
            message: self::MESSAGE
        );
    }
}
