<?php

declare(strict_types=1);

namespace BitBag\SyliusPrzelewy24Plugin\Shared\Synchronizer;

use BitBag\SyliusPrzelewy24Plugin\Shared\Interface\PaymentMethodAwareInterface;
use BitBag\SyliusPrzelewy24Plugin\Shared\Interface\PayloadAwareInterface;

interface SynchronizableRequestInterface extends
    PayloadAwareInterface,
    PaymentMethodAwareInterface
{
}
