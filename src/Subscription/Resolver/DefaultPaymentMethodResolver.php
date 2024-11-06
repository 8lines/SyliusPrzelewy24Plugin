<?php

declare(strict_types=1);

namespace BitBag\SyliusPrzelewy24Plugin\Subscription\Resolver;

use BitBag\SyliusPrzelewy24Plugin\Subscription\Entity\RecurringOrderInterface;
use Sylius\Component\Payment\Model\PaymentInterface;
use Sylius\Component\Payment\Model\PaymentMethodInterface;
use Sylius\Component\Payment\Resolver\DefaultPaymentMethodResolverInterface;
use Sylius\Component\Core\Model\PaymentInterface as CorePaymentInterface;

final class DefaultPaymentMethodResolver implements DefaultPaymentMethodResolverInterface
{
    public function __construct(
        private readonly DefaultPaymentMethodResolverInterface $decoratedDefaultPaymentMethodResolver,
        private readonly Przelewy24SubscriptionPaymentMethodResolverInterface $przelewy24SubscriptionPaymentMethodResolver,
    ) {
    }

    public function getDefaultPaymentMethod(PaymentInterface $payment): PaymentMethodInterface
    {
        if (false === $payment instanceof CorePaymentInterface) {
            return $this->decoratedDefaultPaymentMethodResolver->getDefaultPaymentMethod($payment);
        }

        if (false === $payment->getOrder() instanceof RecurringOrderInterface) {
            return $this->decoratedDefaultPaymentMethodResolver->getDefaultPaymentMethod($payment);
        }

        /** @var RecurringOrderInterface $recurringOrder */
        $recurringOrder = $payment->getOrder();

        if (false === $recurringOrder->getPrzelewy24Order()->isRecurring()) {
            return $this->decoratedDefaultPaymentMethodResolver->getDefaultPaymentMethod($payment);
        }

        $przelewy24SubscriptionPaymentMethod = $this->przelewy24SubscriptionPaymentMethodResolver->resolve();

        if (null === $przelewy24SubscriptionPaymentMethod) {
            return $this->decoratedDefaultPaymentMethodResolver->getDefaultPaymentMethod($payment);
        }

        return $przelewy24SubscriptionPaymentMethod;
    }
}
