<?php

declare(strict_types=1);

namespace BitBag\SyliusPrzelewy24Plugin\Subscription\Resolver\PaymentMethod;

use Sylius\Component\Core\Model\PaymentMethodInterface;

interface SubscriptionPaymentMethodResolverInterface
{
    public function resolve(): ?PaymentMethodInterface;
}
