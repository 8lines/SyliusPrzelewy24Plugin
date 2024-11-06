<?php

declare(strict_types=1);

namespace BitBag\SyliusPrzelewy24Plugin\Subscription\StateProcessor;

use BitBag\SyliusPrzelewy24Plugin\Subscription\CommandDispatcher\RegisterNewSubscriptionDispatcherInterface;
use BitBag\SyliusPrzelewy24Plugin\Subscription\CommandDispatcher\SavePrzelewy24CreditCardIfNotExistsDispatcherInterface;
use BitBag\SyliusPrzelewy24Plugin\Subscription\Counter\Przelewy24SubscriptionScheduleIntervalFailureCounter;
use BitBag\SyliusPrzelewy24Plugin\Subscription\Entity\CustomerInterface;
use BitBag\SyliusPrzelewy24Plugin\Subscription\Entity\RecurringOrderInterface;
use BitBag\SyliusPrzelewy24Plugin\Subscription\Przelewy24SubscriptionGatewayFactory;
use BitBag\SyliusPrzelewy24Plugin\Subscription\StateResolver\Przelewy24SubscriptionScheduleIntervalStateResolverInterface;
use Sylius\Component\Core\Model\PaymentInterface;
use Sylius\Component\Core\Model\PaymentMethodInterface;
use Webmozart\Assert\Assert;

final class SyliusPaymentStateProcessor implements SyliusPaymentStateProcessorInterface
{
    public function __construct(
        private readonly Przelewy24SubscriptionScheduleIntervalFailureCounter $przelewy24SubscriptionScheduleIntervalFailureCounter,
        private readonly Przelewy24SubscriptionScheduleIntervalStateResolverInterface $przelewy24SubscriptionScheduleIntervalStateResolver,
        private readonly RegisterNewSubscriptionDispatcherInterface $registerNewSubscriptionDispatcher,
        private readonly SavePrzelewy24CreditCardIfNotExistsDispatcherInterface $savePrzelewy24CreditCardIfNotExistsDispatcher,
    ) {
    }

    public function process(PaymentInterface $payment): void
    {
        if (false === $this->isProcessable($payment)) {
            return;
        }

        /** @var RecurringOrderInterface $recurringOrder */
        $recurringOrder = $payment->getOrder();

        $paymentCompleted = PaymentInterface::STATE_COMPLETED === $payment->getState();
        $initialRecurringPayment = true === $payment->getDetails()['initialTransaction'] ?? false;

        if (true === $paymentCompleted && true === $initialRecurringPayment) {
            /** @var CustomerInterface $customer */
            $customer = $recurringOrder->getCustomer();

            Assert::notNull($customer->getPrzelewy24Customer());
            Assert::notNull($payment->getDetails()['cardMask'] ?? null);
            Assert::notNull($payment->getDetails()['cardDate'] ?? null);
            Assert::notNull($payment->getDetails()['cardRefId'] ?? null);

            $this->savePrzelewy24CreditCardIfNotExistsDispatcher->dispatch(
                przelewy24CustomerId: $customer->getPrzelewy24Customer()->getId(),
                cardMask: $payment->getDetails()['cardMask'],
                cardDate: $payment->getDetails()['cardDate'],
                cardRefId: $payment->getDetails()['cardRefId'],
            );

            $this->registerNewSubscriptionDispatcher->dispatch(
                syliusRecurringOrderId: $recurringOrder->getId(),
            );

            return;
        }

        $interval = $recurringOrder->getPrzelewy24Order()->getSubscription()->getSchedule()->getIntervalBySequence(
            sequence: $recurringOrder->getPrzelewy24Order()->getRecurringSequenceIndex(),
        );

        $paymentFailed = PaymentInterface::STATE_FAILED === $payment->getState();

        if (true === $paymentFailed) {
            $this->przelewy24SubscriptionScheduleIntervalFailureCounter->incrementFailsCount($interval);
        }

        $this->przelewy24SubscriptionScheduleIntervalStateResolver->resolve($interval);
    }

    private function isProcessable(PaymentInterface $payment): bool
    {
        if (false === $payment->getMethod() instanceof PaymentMethodInterface) {
            return false;
        }

        /** @var PaymentMethodInterface $paymentMethod */
        $paymentMethod = $payment->getMethod();

        if (Przelewy24SubscriptionGatewayFactory::GATEWAY_NAME !== $paymentMethod->getGatewayConfig()?->getFactoryName()) {
            return false;
        }

        if (false === $payment->getOrder() instanceof RecurringOrderInterface) {
            return false;
        }

        /** @var RecurringOrderInterface $recurringOrder */
        $recurringOrder = $payment->getOrder();

        if (false === $recurringOrder->getPrzelewy24Order()->isRecurring()) {
            return false;
        }

        return true;
    }
}
