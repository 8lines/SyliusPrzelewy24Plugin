<?php

declare(strict_types=1);

namespace BitBag\SyliusPrzelewy24Plugin\Subscription\SubscriptionProcessing;

use BitBag\SyliusPrzelewy24Plugin\Subscription\Applicator\SubscriptionBasedRouterContextApplicatorInterface;
use BitBag\SyliusPrzelewy24Plugin\Subscription\Cloner\OrderClonerInterface;
use BitBag\SyliusPrzelewy24Plugin\Subscription\Entity\RecurringSyliusOrderInterface;
use BitBag\SyliusPrzelewy24Plugin\Subscription\Entity\SubscriptionInterface;
use BitBag\SyliusPrzelewy24Plugin\Subscription\Entity\SubscriptionIntervalInterface;
use BitBag\SyliusPrzelewy24Plugin\Subscription\Repository\SubscriptionRepositoryInterface;
use BitBag\SyliusPrzelewy24Plugin\Subscription\Resolver\PaymentMethod\SubscriptionPaymentMethodResolverInterface;
use BitBag\SyliusPrzelewy24Plugin\Subscription\Transitions\SubscriptionIntervalTransitions;
use Sylius\Abstraction\StateMachine\StateMachineInterface;
use Sylius\Bundle\PaymentBundle\Announcer\PaymentRequestAnnouncerInterface;
use Sylius\Component\Core\Model\PaymentInterface;
use Sylius\Component\Payment\Factory\PaymentFactoryInterface;
use Sylius\Component\Payment\Factory\PaymentRequestFactoryInterface;
use Sylius\Component\Payment\Model\PaymentRequestInterface;
use Sylius\Component\Payment\Repository\PaymentRequestRepositoryInterface;
use Sylius\Resource\Doctrine\Persistence\RepositoryInterface;
use Webmozart\Assert\Assert;

final readonly class SubscriptionCurrentIntervalPaymentProcessor implements SubscriptionProcessorInterface
{
    public function __construct(
        private StateMachineInterface $stateMachine,
        private SubscriptionRepositoryInterface $subscriptionRepository,
        private OrderClonerInterface $orderCloner,
        private PaymentFactoryInterface $paymentFactory,
        private SubscriptionPaymentMethodResolverInterface $subscriptionPaymentMethodResolver,
        private PaymentRequestFactoryInterface $paymentRequestFactory,
        private SubscriptionBasedRouterContextApplicatorInterface $subscriptionBasedRouterContextApplicator,
        private RepositoryInterface $syliusOrderRepository,
        private RepositoryInterface $syliusPaymentRepository,
        private PaymentRequestRepositoryInterface $syliusPaymentRequestRepository,
        private PaymentRequestAnnouncerInterface $paymentRequestAnnouncer,
    ) {
    }

    public function process(SubscriptionInterface $subscription): void
    {
        $schedule = $subscription->getSchedule();
        $currentInterval = $schedule->getCurrentInterval();

        if (null === $currentInterval) {
            return;
        }

        $intervalAwaitingPayment = $this->stateMachine->can(
            subject: $currentInterval,
            graphName: SubscriptionIntervalTransitions::GRAPH,
            transition: SubscriptionIntervalTransitions::TRANSITION_AWAIT_PAYMENT,
        );

        if (false === $intervalAwaitingPayment) {
            return;
        }

        $this->stateMachine->apply(
            subject: $currentInterval,
            graphName: SubscriptionIntervalTransitions::GRAPH,
            transition: SubscriptionIntervalTransitions::TRANSITION_AWAIT_PAYMENT,
        );

        $this->subscriptionRepository->add($subscription);

        $this->subscriptionBasedRouterContextApplicator->apply(
            subscription: $subscription,
        );

        $order = $this->createOrderForInterval(
            subscription: $subscription,
            interval: $currentInterval,
        );

        $this->syliusOrderRepository->add($order);

        $payment = $this->createPaymentForOrder(
            order: $order,
        );

        $this->syliusPaymentRepository->add($payment);
        $this->syliusOrderRepository->add($order);

        $currentInterval->setOrder($order->getRecurringPrzelewy24Order());

        $this->subscriptionRepository->add($subscription);

        $paymentRequest = $this->createPaymentRequestForPayment(
            subscription: $subscription,
            payment: $payment,
        );

        $this->syliusPaymentRequestRepository->add($paymentRequest);

        $this->paymentRequestAnnouncer->dispatchPaymentRequestCommand(
            paymentRequest: $paymentRequest,
        );
    }

    private function createOrderForInterval(
        SubscriptionInterface $subscription,
        SubscriptionIntervalInterface $interval,
    ): RecurringSyliusOrderInterface {
        /** @var RecurringSyliusOrderInterface $baseOrder */
        $baseOrder = $subscription->getBaseOrder()?->getSyliusOrder();

        Assert::notNull(
            value: $baseOrder,
            message: 'Subscription has no base order',
        );

        $order = $this->orderCloner->clone($baseOrder);
        $przelewy24Order = $order->getRecurringPrzelewy24Order();

        $przelewy24Order->setRecurring(true);
        $przelewy24Order->setRecurringSequenceIndex($interval->getSequence());
        $przelewy24Order->setSubscription($subscription);

        return $order;
    }

    private function createPaymentForOrder(
        RecurringSyliusOrderInterface $order,
    ): PaymentInterface {
        Assert::notNull(
            value: $order->getCurrencyCode(),
            message: 'Order has no currency code',
        );

        /** @var PaymentInterface $payment */
        $payment = $this->paymentFactory->createWithAmountAndCurrencyCode(
            amount: $order->getTotal(),
            currency: $order->getCurrencyCode(),
        );

        $paymentMethod = $this->subscriptionPaymentMethodResolver->resolve();

        Assert::notNull(
            value: $paymentMethod,
            message: 'No payment method found for subscription',
        );

        $payment->setMethod($paymentMethod);
        $payment->setState(PaymentInterface::STATE_NEW);
        $payment->setOrder($order);

        $order->addPayment($payment);

        return $payment;
    }

    private function createPaymentRequestForPayment(
        SubscriptionInterface $subscription,
        PaymentInterface $payment,
    ): PaymentRequestInterface {
        $paymentRequest = $this->paymentRequestFactory->create(
            payment: $payment,
            paymentMethod: $payment->getMethod(),
        );

        /** @var string $cardRefId */
        $cardRefId = $subscription->getConfiguration()?->getCard()?->getRefId();

        Assert::notNull(
            value: $cardRefId,
            message: 'Subscription has no credit card provided',
        );

        $paymentRequest->setPayload([
            'cardRefId' => $cardRefId,
        ]);

        return $paymentRequest;
    }
}
