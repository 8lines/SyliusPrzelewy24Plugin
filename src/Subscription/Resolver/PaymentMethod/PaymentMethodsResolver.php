<?php

declare(strict_types=1);

namespace BitBag\SyliusPrzelewy24Plugin\Subscription\Resolver\PaymentMethod;

use BitBag\SyliusPrzelewy24Plugin\Subscription\Entity\RecurringSyliusOrderInterface;
use BitBag\SyliusPrzelewy24Plugin\Subscription\Przelewy24SubscriptionGateway;
use Sylius\Component\Core\Model\PaymentInterface as CorePaymentInterface;
use Sylius\Component\Core\Model\PaymentMethodInterface;
use Sylius\Component\Payment\Model\PaymentInterface;
use Sylius\Component\Payment\Resolver\PaymentMethodsResolverInterface;

final readonly class PaymentMethodsResolver implements PaymentMethodsResolverInterface
{
    public function __construct(
        private PaymentMethodsResolverInterface $decoratedPaymentMethodsResolver,
    ) {
    }

    public function getSupportedMethods(PaymentInterface $subject): array
    {
        $resolvedMethods = $this->decoratedPaymentMethodsResolver->getSupportedMethods($subject);

        if (false === $subject instanceof CorePaymentInterface) {
            return $resolvedMethods;
        }

        if (false === $subject->getOrder() instanceof RecurringSyliusOrderInterface) {
            return $resolvedMethods;
        }

        /** @var RecurringSyliusOrderInterface $order */
        $order = $subject->getOrder();

        if (false === $order->getRecurringPrzelewy24Order()->isRecurring()) {
            return \array_filter(
                array: $resolvedMethods,
                callback: fn (PaymentMethodInterface $paymentMethod) =>
                    Przelewy24SubscriptionGateway::GATEWAY_NAME !== $paymentMethod->getGatewayConfig()->getFactoryName()
            );
        }

        return \array_filter(
            array: $resolvedMethods,
            callback: fn (PaymentMethodInterface $paymentMethod) =>
                Przelewy24SubscriptionGateway::GATEWAY_NAME === $paymentMethod->getGatewayConfig()->getFactoryName()
        );
    }

    public function supports(PaymentInterface $subject): bool
    {
        return $this->decoratedPaymentMethodsResolver->supports($subject);
    }
}
