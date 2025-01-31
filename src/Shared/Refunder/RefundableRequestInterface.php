<?php

declare(strict_types=1);

namespace BitBag\SyliusPrzelewy24Plugin\Shared\Refunder;

use BitBag\SyliusPrzelewy24Plugin\Shared\Interface\PayloadAwareInterface;
use BitBag\SyliusPrzelewy24Plugin\Shared\Interface\PaymentMethodAwareInterface;

interface RefundableRequestInterface extends
    PayloadAwareInterface,
    PaymentMethodAwareInterface
{
}
