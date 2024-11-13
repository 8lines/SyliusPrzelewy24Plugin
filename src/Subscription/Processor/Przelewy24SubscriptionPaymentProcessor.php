<?php

declare(strict_types=1);

namespace BitBag\SyliusPrzelewy24Plugin\Subscription\Processor;

use BitBag\SyliusPrzelewy24Plugin\Subscription\Applicator\PaymentFailedStateApplicatorInterface;
use BitBag\SyliusPrzelewy24Plugin\Subscription\Applicator\Przelewy24SubscriptionBasedRouterContextApplicatorInterface;
use BitBag\SyliusPrzelewy24Plugin\Subscription\Checker\IsPrzelewy24SubscriptionPaymentFailedCheckerInterface;
use BitBag\SyliusPrzelewy24Plugin\Subscription\Cloner\RecurringOrderClonerInterface;
use BitBag\SyliusPrzelewy24Plugin\Subscription\Entity\Przelewy24SubscriptionInterface;
use BitBag\SyliusPrzelewy24Plugin\Subscription\Entity\Przelewy24SubscriptionScheduleIntervalInterface;
use BitBag\SyliusPrzelewy24Plugin\Subscription\Entity\RecurringOrderInterface;
use BitBag\SyliusPrzelewy24Plugin\Subscription\Przelewy24SubscriptionGatewayFactory;
use BitBag\SyliusPrzelewy24Plugin\Subscription\Repository\Przelewy24SubscriptionRepositoryInterface;
use BitBag\SyliusPrzelewy24Plugin\Subscription\Resolver\Przelewy24SubscriptionPaymentMethodResolverInterface;
use Payum\Core\Payum;
use Payum\Core\Request\Capture;
use Payum\Core\Request\Convert;
use Sylius\Component\Core\Model\PaymentInterface;
use Sylius\Component\Payment\Factory\PaymentFactoryInterface;
use Sylius\Resource\Doctrine\Persistence\RepositoryInterface;
use Webmozart\Assert\Assert;

final class Przelewy24SubscriptionPaymentProcessor implements Przelewy24SubscriptionPaymentProcessorInterface
{
    public function __construct(
        private readonly Payum $payum,
        private readonly PaymentFactoryInterface $paymentFactory,
        private readonly RepositoryInterface $syliusOrderRepository,
        private readonly RepositoryInterface $syliusPaymentRepository,
        private readonly RecurringOrderClonerInterface $recurringOrderCloner,
        private readonly Przelewy24SubscriptionRepositoryInterface $przelewy24SubscriptionRepository,
        private readonly Przelewy24SubscriptionPaymentMethodResolverInterface $przelewy24SubscriptionPaymentMethodResolver,
        private readonly Przelewy24SubscriptionBasedRouterContextApplicatorInterface $przelewy24SubscriptionBasedRouterContextApplicator,
        private readonly IsPrzelewy24SubscriptionPaymentFailedCheckerInterface $isPrzelewy24SubscriptionPaymentFailedChecker,
        private readonly PaymentFailedStateApplicatorInterface $paymentFailedStateApplicator,
    ) {
    }

    public function processRecurringPayment(
        Przelewy24SubscriptionInterface $subscription,
        int $sequence,
    ): void {
        Assert::greaterThanEq(
            value: $sequence,
            limit: 1,
            message: 'Sequence must be greater than or equal to 1',
        );

        /** @var Przelewy24SubscriptionScheduleIntervalInterface $interval */
        $interval = $subscription->getSchedule()->getIntervalBySequence($sequence);

        Assert::notNull(
            value: $interval,
            message: 'Interval not found in subscription schedule',
        );

        $recurringOrder = $this->createRecurringOrderForInterval($subscription, $interval);
        $recurringPayment = $this->createPaymentForRecurringOrder($subscription, $recurringOrder);

        $recurringOrder->addPayment($recurringPayment);

        $this->syliusOrderRepository->add($recurringOrder);

        $interval->setOrder($recurringOrder->getPrzelewy24Order());

        $this->przelewy24SubscriptionRepository->add($subscription);

        $this->processPaymentUsingPayum($recurringPayment);
    }

    private function createRecurringOrderForInterval(
        Przelewy24SubscriptionInterface $subscription,
        Przelewy24SubscriptionScheduleIntervalInterface $interval,
    ): RecurringOrderInterface {
        /** @var RecurringOrderInterface $baseRecurringOrder */
        $baseRecurringOrder = $subscription->getBaseOrder()?->getSyliusOrder();

        Assert::notNull(
            value: $baseRecurringOrder,
            message: 'Subscription has no base recurring order',
        );

        $recurringOrder = $this->recurringOrderCloner->clone($baseRecurringOrder);
        $przelewy24Order = $recurringOrder->getPrzelewy24Order();

        $przelewy24Order->setRecurring(true);
        $przelewy24Order->setRecurringSequenceIndex($interval->getSequence());
        $przelewy24Order->setSubscription($subscription);

        return $recurringOrder;
    }

    private function createPaymentForRecurringOrder(
        Przelewy24SubscriptionInterface $subscription,
        RecurringOrderInterface $recurringOrder
    ): PaymentInterface {
        Assert::notNull(
            value: $recurringOrder->getCurrencyCode(),
            message: 'Recurring order has no currency code',
        );

        /** @var PaymentInterface $recurringPayment */
        $recurringPayment = $this->paymentFactory->createWithAmountAndCurrencyCode(
            amount: $recurringOrder->getTotal(),
            currency: $recurringOrder->getCurrencyCode(),
        );

        $przelewy24SubscriptionPaymentMethod =
            $this->przelewy24SubscriptionPaymentMethodResolver->resolve();

        Assert::notNull(
            value: $przelewy24SubscriptionPaymentMethod,
            message: 'No payment method found for Przelewy24 subscription',
        );

        /** @var string $cardRefId */
        $cardRefId = $subscription->getConfiguration()?->getCreditCard()?->getCardRefId();

        Assert::notNull(
            value: $cardRefId,
            message: 'Subscription has no credit card provided',
        );

        $recurringPayment->setMethod($przelewy24SubscriptionPaymentMethod);
        $recurringPayment->setState(PaymentInterface::STATE_NEW);

        $recurringPayment->setDetails([
            'cardRefId' => $cardRefId,
        ]);

        return $recurringPayment;
    }

    private function processPaymentUsingPayum(PaymentInterface $recurringPayment): void
    {
        /** @var RecurringOrderInterface $recurringOrder */
        $recurringOrder = $recurringPayment->getOrder();

        $this->przelewy24SubscriptionBasedRouterContextApplicator->apply(
            subscription: $recurringOrder->getPrzelewy24Order()->getSubscription(),
        );

        $gateway = $this->payum->getGateway(Przelewy24SubscriptionGatewayFactory::GATEWAY_NAME);

        $gateway->execute($convert = new Convert($recurringPayment, 'array'));

        $recurringPayment->setDetails($convert->getResult());
        $recurringPayment->setState(PaymentInterface::STATE_PROCESSING);

        $this->syliusPaymentRepository->add($recurringPayment);

        $captureToken = $this->payum->getTokenFactory()->createCaptureToken(
            gatewayName: Przelewy24SubscriptionGatewayFactory::GATEWAY_NAME,
            model: $recurringPayment,
            afterPath: 'sylius_shop_order_thank_you',
        );

        $capture = new Capture($captureToken);
        $capture->setModel($recurringPayment);

        $gateway->execute($capture);

        if (true === $this->isPrzelewy24SubscriptionPaymentFailedChecker->isFailed($recurringPayment)) {
            $this->paymentFailedStateApplicator->apply($recurringPayment);
        }
    }
}
