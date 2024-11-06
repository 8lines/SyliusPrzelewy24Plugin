<?php

declare(strict_types=1);

namespace BitBag\SyliusPrzelewy24Plugin\Subscription\Resolver;

use BitBag\SyliusPrzelewy24Plugin\Subscription\Entity\RecurringOrderInterface;
use BitBag\SyliusPrzelewy24Plugin\Subscription\Przelewy24SubscriptionGatewayFactory;
use Sylius\Component\Core\Model\PaymentMethodInterface;
use Sylius\Component\Payment\Model\PaymentInterface;
use Sylius\Component\Payment\Resolver\PaymentMethodsResolverInterface;
use Sylius\Component\Core\Model\PaymentInterface as CorePaymentInterface;

final class PaymentMethodsResolver implements PaymentMethodsResolverInterface
{
    public function __construct(
        private readonly PaymentMethodsResolverInterface $decoratedPaymentMethodsResolver,
    ) {
    }

    public function getSupportedMethods(PaymentInterface $subject): array
    {
        $resolvedMethods = $this->decoratedPaymentMethodsResolver->getSupportedMethods($subject);

        if (false === $subject instanceof CorePaymentInterface) {
            return $resolvedMethods;
        }

        if (false === $subject->getOrder() instanceof RecurringOrderInterface) {
            return $resolvedMethods;
        }

        /** @var RecurringOrderInterface $recurringOrder */
        $recurringOrder = $subject->getOrder();

        if (false === $recurringOrder->getPrzelewy24Order()->isRecurring()) {
            return \array_filter(
                array: $resolvedMethods,
                callback: fn (PaymentMethodInterface $paymentMethod) =>
                    Przelewy24SubscriptionGatewayFactory::GATEWAY_NAME !== $paymentMethod->getGatewayConfig()->getFactoryName()
            );
        }

        return \array_filter(
            array: $resolvedMethods,
            callback: fn (PaymentMethodInterface $paymentMethod) =>
                Przelewy24SubscriptionGatewayFactory::GATEWAY_NAME === $paymentMethod->getGatewayConfig()->getFactoryName()
        );
    }

    public function supports(PaymentInterface $subject): bool
    {
        return $this->decoratedPaymentMethodsResolver->supports($subject);
    }
}
