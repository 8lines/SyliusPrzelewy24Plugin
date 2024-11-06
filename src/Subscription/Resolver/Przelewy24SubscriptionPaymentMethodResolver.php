<?php

declare(strict_types=1);

namespace BitBag\SyliusPrzelewy24Plugin\Subscription\Resolver;

use BitBag\SyliusPrzelewy24Plugin\Subscription\Przelewy24SubscriptionGatewayFactory;
use Sylius\Component\Core\Model\PaymentMethodInterface;
use Sylius\Resource\Doctrine\Persistence\RepositoryInterface;

final class Przelewy24SubscriptionPaymentMethodResolver implements Przelewy24SubscriptionPaymentMethodResolverInterface
{
    public function __construct(
        private readonly RepositoryInterface $syliusPaymentMethodRepository,
    ) {
    }

    public function resolve(): ?PaymentMethodInterface
    {
        $methods = $this->syliusPaymentMethodRepository->findAll();

        foreach ($methods as $method) {
            $factoryName = $method->getGatewayConfig()?->getFactoryName();

            if (Przelewy24SubscriptionGatewayFactory::GATEWAY_NAME === $factoryName) {
                return $method;
            }
        }

        return null;
    }
}
