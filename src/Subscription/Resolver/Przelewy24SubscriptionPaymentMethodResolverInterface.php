<?php

declare(strict_types=1);

namespace BitBag\SyliusPrzelewy24Plugin\Subscription\Resolver;

use Sylius\Component\Core\Model\PaymentMethodInterface;

interface Przelewy24SubscriptionPaymentMethodResolverInterface
{
    public function resolve(): ?PaymentMethodInterface;
}
