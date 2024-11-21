<?php

declare(strict_types=1);

namespace BitBag\SyliusPrzelewy24Plugin\Subscription\EventListener\Workflow\Payment;

use BitBag\SyliusPrzelewy24Plugin\Subscription\Counter\SubscriptionIntervalFailureCounterInterface;
use BitBag\SyliusPrzelewy24Plugin\Subscription\Entity\SubscriptionInterface;
use BitBag\SyliusPrzelewy24Plugin\Subscription\Entity\RecurringSyliusOrderInterface;
use Sylius\Component\Core\Model\PaymentInterface;
use Symfony\Component\Workflow\Event\CompletedEvent;
use Webmozart\Assert\Assert;

final readonly class IncrementSubscriptionIntervalFailureCountListener
{
    public function __construct(
        private SubscriptionIntervalFailureCounterInterface $subscriptionIntervalFailureCounter,
    ) {
    }

    public function __invoke(CompletedEvent $event): void
    {
        /** @var PaymentInterface $payment */
        $payment = $event->getSubject();

        /** @var RecurringSyliusOrderInterface $order */
        $order = $payment->getOrder();

        Assert::isInstanceOf(
            value: $order,
            class: RecurringSyliusOrderInterface::class,
            message: 'SyliusOrder must be instance of %2$s, but is %s.'
        );

        if (false === $order->getRecurringPrzelewy24Order()->isRecurring()) {
            return;
        }

        /** @var SubscriptionInterface $subscription */
        $subscription = $order->getRecurringPrzelewy24Order()->getSubscription();

        Assert::notNull(
            value: $subscription,
            message: 'Subscription cannot be null.'
        );

        $sequence = $order->getRecurringPrzelewy24Order()->getRecurringSequenceIndex();

        Assert::notNull(
            value: $sequence,
            message: 'Sequence cannot be null.'
        );

        $interval = $subscription->getSchedule()->getIntervalBySequence(
            sequence: $sequence,
        );

        $this->subscriptionIntervalFailureCounter->incrementFailsCount($interval);
    }
}
