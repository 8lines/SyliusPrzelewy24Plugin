<?php

declare(strict_types=1);

namespace BitBag\SyliusPrzelewy24Plugin\Subscription\Factory;

use BitBag\SyliusPrzelewy24Plugin\Subscription\Entity\SyliusCustomerAsSubscriberInterface;
use BitBag\SyliusPrzelewy24Plugin\Subscription\Entity\Subscription;
use BitBag\SyliusPrzelewy24Plugin\Subscription\Entity\SubscriptionInterface;
use BitBag\SyliusPrzelewy24Plugin\Subscription\Entity\SubscriptionIntervalInterface;
use BitBag\SyliusPrzelewy24Plugin\Subscription\Entity\RecurringSyliusOrderInterface;
use BitBag\SyliusPrzelewy24Plugin\Subscription\Generator\SubscriptionScheduleGeneratorInterface;
use BitBag\SyliusPrzelewy24Plugin\Subscription\Przelewy24SubscriptionGateway;
use Sylius\Component\Core\Model\PaymentInterface;
use Sylius\Component\Core\Model\PaymentMethodInterface;
use Sylius\Resource\Factory\FactoryInterface;
use Symfony\Component\Clock\ClockInterface;
use Webmozart\Assert\Assert;

final readonly class SubscriptionFactory implements SubscriptionFactoryInterface
{
    public function __construct(
        private FactoryInterface $decoratedFactory,
        private ClockInterface $clock,
        private SubscriptionConfigurationFactoryInterface $subscriptionConfigurationFactory,
        private SubscriptionScheduleGeneratorInterface $subscriptionScheduleGenerator,
    ) {
    }

    public function createNew(): SubscriptionInterface
    {
        return $this->decoratedFactory->createNew();
    }

    public function createFromOrder(RecurringSyliusOrderInterface $order): SubscriptionInterface
    {
        Assert::eq(
            value: $order->getRecurringPrzelewy24Order()->getRecurringSequenceIndex(),
            expect: SubscriptionInterface::INITIAL_SEQUENCE,
            message: 'Only first recurring order can be used to create subscription',
        );

        /** @var SyliusCustomerAsSubscriberInterface $customer */
        $customer = $order->getCustomer();

        Assert::notNull(
            value: $customer,
            message: 'SyliusOrder must have customer',
        );

        $configuration = $this->subscriptionConfigurationFactory->createFromOrder(
            order: $order,
        );

        /** @var int $recurringTimes */
        $recurringTimes = $configuration->getRecurringTimes();

        /** @var int $recurringIntervalInDays */
        $recurringIntervalInDays = $configuration->getRecurringIntervalInDays();

        $startsAt = $this->clock->now();
        $endsAt = $startsAt->modify('+'. ($recurringTimes * $recurringIntervalInDays) . ' days');

        $schedule = $this->subscriptionScheduleGenerator->generate(
            startsAt: $startsAt,
            recurringTimes: $configuration->getRecurringTimes(),
            recurringIntervalInDays: $configuration->getRecurringIntervalInDays(),
        );

        $initialInterval = $schedule->getIntervalBySequence(
            sequence: SubscriptionInterface::INITIAL_SEQUENCE,
        );

        $initialInterval->setState(SubscriptionIntervalInterface::STATE_FULFILLED);
        $initialInterval->setOrder($order->getRecurringPrzelewy24Order());

        $subscription = $this->createNew();

        $subscription->setOwner($customer->getPrzelewy24Subscriber());
        $subscription->setStartsAt($startsAt);
        $subscription->setEndsAt($endsAt);
        $subscription->setConfiguration($configuration);
        $subscription->setSchedule($schedule);
        $subscription->setBaseOrder($order->getRecurringPrzelewy24Order());

        return $subscription;
    }
}
