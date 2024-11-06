<?php

declare(strict_types=1);

namespace BitBag\SyliusPrzelewy24Plugin\Subscription\Factory;

use BitBag\SyliusPrzelewy24Plugin\Subscription\Entity\CustomerInterface;
use BitBag\SyliusPrzelewy24Plugin\Subscription\Entity\Przelewy24Subscription;
use BitBag\SyliusPrzelewy24Plugin\Subscription\Entity\Przelewy24SubscriptionInterface;
use BitBag\SyliusPrzelewy24Plugin\Subscription\Entity\Przelewy24SubscriptionScheduleIntervalInterface;
use BitBag\SyliusPrzelewy24Plugin\Subscription\Entity\RecurringOrderInterface;
use BitBag\SyliusPrzelewy24Plugin\Subscription\Generator\Przelewy24SubscriptionScheduleGeneratorInterface;
use BitBag\SyliusPrzelewy24Plugin\Subscription\Przelewy24SubscriptionGatewayFactory;
use Sylius\Component\Core\Model\PaymentInterface;
use Sylius\Component\Core\Model\PaymentMethodInterface;
use Sylius\Resource\Factory\FactoryInterface;
use Symfony\Component\Clock\ClockInterface;
use Webmozart\Assert\Assert;

final class Przelewy24SubscriptionFactory implements Przelewy24SubscriptionFactoryInterface
{
    public function __construct(
        private readonly FactoryInterface $decoratedFactory,
        private readonly ClockInterface $clock,
        private readonly Przelewy24SubscriptionConfigurationFactoryInterface $przelewy24SubscriptionConfigurationFactory,
        private readonly Przelewy24SubscriptionScheduleGeneratorInterface $przelewy24SubscriptionScheduleGenerator,
    ) {
    }

    public function createNew(): Przelewy24SubscriptionInterface
    {
        return $this->decoratedFactory->createNew();
    }

    public function createFromRecurringOrder(
        RecurringOrderInterface $recurringOrder,
    ): Przelewy24SubscriptionInterface {
        Assert::eq(
            value: $recurringOrder->getPrzelewy24Order()->getRecurringSequenceIndex(),
            expect: Przelewy24Subscription::INITIAL_SEQUENCE,
            message: 'Only first recurring order can be used to create subscription',
        );

        Assert::notNull($recurringOrder->getCustomer());

        /** @var CustomerInterface $syliusCustomer */
        $syliusCustomer = $recurringOrder->getCustomer();

        Assert::notNull($syliusCustomer);

        Assert::notNull($recurringOrder->getLastPayment());
        Assert::notNull($recurringOrder->getLastPayment()?->getMethod());

        /** @var PaymentInterface $recurringPayment */
        $recurringPayment = $recurringOrder->getLastPayment();

        /** @var PaymentMethodInterface $recurringPaymentMethod */
        $recurringPaymentMethod = $recurringPayment->getMethod();

        Assert::eq(
            value: $recurringPaymentMethod->getGatewayConfig()->getFactoryName(),
            expect: Przelewy24SubscriptionGatewayFactory::GATEWAY_NAME,
            message: 'Only Przelewy24 payment method can be used to create subscription',
        );

        $configuration = $this->przelewy24SubscriptionConfigurationFactory->createFromRecurringOrder(
            recurringOrder: $recurringOrder,
        );

        /** @var int $recurringTimes */
        $recurringTimes = $configuration->getRecurringTimes();

        /** @var int $recurringIntervalInDays */
        $recurringIntervalInDays = $configuration->getRecurringIntervalInDays();

        $startsAt = $this->clock->now();
        $endsAt = $startsAt->modify('+'. ($recurringTimes * $recurringIntervalInDays) . ' days');

        $schedule = $this->przelewy24SubscriptionScheduleGenerator->generate(
            startsAt: $startsAt,
            recurringTimes: $configuration->getRecurringTimes(),
            recurringIntervalInDays: $configuration->getRecurringIntervalInDays(),
        );

        $initialInterval = $schedule->getIntervalBySequence(
            sequence: Przelewy24Subscription::INITIAL_SEQUENCE,
        );

        $initialInterval->setState(Przelewy24SubscriptionScheduleIntervalInterface::STATE_FULFILLED);
        $initialInterval->setSyliusOrder($recurringOrder);
        $initialInterval->setSyliusPayment($recurringPayment);

        $subscription = $this->createNew();

        $subscription->setOwner($syliusCustomer->getPrzelewy24Customer());
        $subscription->setStartsAt($startsAt);
        $subscription->setEndsAt($endsAt);
        $subscription->setConfiguration($configuration);
        $subscription->setSchedule($schedule);
        $subscription->setBaseRecurringOrder($recurringOrder);

        return $subscription;
    }
}
