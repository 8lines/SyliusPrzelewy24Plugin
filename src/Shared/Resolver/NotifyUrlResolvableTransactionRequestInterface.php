<?php

declare(strict_types=1);

namespace BitBag\SyliusPrzelewy24Plugin\Shared\Resolver;

use BitBag\SyliusPrzelewy24Plugin\Shared\Interface\HashAwareInterface;
use BitBag\SyliusPrzelewy24Plugin\Shared\Interface\PaymentMethodAwareInterface;

interface NotifyUrlResolvableTransactionRequestInterface extends
    HashAwareInterface,
    PaymentMethodAwareInterface
{
}
