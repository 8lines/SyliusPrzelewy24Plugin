<?php

declare(strict_types=1);

namespace BitBag\SyliusPrzelewy24Plugin\Subscription\Resolver\PaymentMethod;

use BitBag\SyliusPrzelewy24Plugin\Subscription\Przelewy24SubscriptionGateway;
use Sylius\Component\Core\Model\PaymentMethodInterface;
use Sylius\Resource\Doctrine\Persistence\RepositoryInterface;

final readonly class SubscriptionPaymentMethodResolver implements SubscriptionPaymentMethodResolverInterface
{
    public function __construct(
        private RepositoryInterface $syliusPaymentMethodRepository,
    ) {
    }

    public function resolve(): ?PaymentMethodInterface
    {
        $methods = $this->syliusPaymentMethodRepository->findAll();

        foreach ($methods as $method) {
            $factoryName = $method->getGatewayConfig()?->getFactoryName();

            if (Przelewy24SubscriptionGateway::GATEWAY_NAME === $factoryName) {
                return $method;
            }
        }

        return null;
    }
}
