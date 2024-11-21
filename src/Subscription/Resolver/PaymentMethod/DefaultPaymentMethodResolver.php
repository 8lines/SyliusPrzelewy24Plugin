<?php

declare(strict_types=1);

namespace BitBag\SyliusPrzelewy24Plugin\Subscription\Resolver\PaymentMethod;

use BitBag\SyliusPrzelewy24Plugin\Subscription\Entity\RecurringSyliusOrderInterface;
use Sylius\Component\Core\Model\PaymentInterface as CorePaymentInterface;
use Sylius\Component\Payment\Model\PaymentInterface;
use Sylius\Component\Payment\Model\PaymentMethodInterface;
use Sylius\Component\Payment\Resolver\DefaultPaymentMethodResolverInterface;

final readonly class DefaultPaymentMethodResolver implements DefaultPaymentMethodResolverInterface
{
    public function __construct(
        private DefaultPaymentMethodResolverInterface $decoratedDefaultPaymentMethodResolver,
        private SubscriptionPaymentMethodResolverInterface $subscriptionPaymentMethodResolver,
    ) {
    }

    public function getDefaultPaymentMethod(PaymentInterface $payment): PaymentMethodInterface
    {
        if (false === $payment instanceof CorePaymentInterface) {
            return $this->decoratedDefaultPaymentMethodResolver->getDefaultPaymentMethod($payment);
        }

        if (false === $payment->getOrder() instanceof RecurringSyliusOrderInterface) {
            return $this->decoratedDefaultPaymentMethodResolver->getDefaultPaymentMethod($payment);
        }

        /** @var RecurringSyliusOrderInterface $order */
        $order = $payment->getOrder();

        if (false === $order->getRecurringPrzelewy24Order()->isRecurring()) {
            return $this->decoratedDefaultPaymentMethodResolver->getDefaultPaymentMethod($payment);
        }

        $subscriptionPaymentMethod = $this->subscriptionPaymentMethodResolver->resolve();

        if (null === $subscriptionPaymentMethod) {
            return $this->decoratedDefaultPaymentMethodResolver->getDefaultPaymentMethod($payment);
        }

        return $subscriptionPaymentMethod;
    }
}
