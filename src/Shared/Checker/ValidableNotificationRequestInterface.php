<?php

declare(strict_types=1);

namespace BitBag\SyliusPrzelewy24Plugin\Shared\Checker;

use BitBag\SyliusPrzelewy24Plugin\Shared\Synchronizer\SynchronizableRequestInterface;
use BitBag\SyliusPrzelewy24Plugin\Shared\Interface\HttpRequestAwareInterface;
use BitBag\SyliusPrzelewy24Plugin\Shared\Interface\OrderTotalAwareInterface;
use BitBag\SyliusPrzelewy24Plugin\Shared\Interface\PaymentMethodAwareInterface;
use BitBag\SyliusPrzelewy24Plugin\Shared\Interface\PayloadAwareInterface;

interface ValidableNotificationRequestInterface extends
    PayloadAwareInterface,
    HttpRequestAwareInterface,
    PaymentMethodAwareInterface,
    OrderTotalAwareInterface,
    SynchronizableRequestInterface
{
}
