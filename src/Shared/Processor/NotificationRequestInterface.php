<?php

declare(strict_types=1);

namespace BitBag\SyliusPrzelewy24Plugin\Shared\Processor;

use BitBag\SyliusPrzelewy24Plugin\Shared\Checker\ValidableNotificationRequestInterface;
use BitBag\SyliusPrzelewy24Plugin\Shared\Verifier\VerifiableRequestInterface;

interface NotificationRequestInterface extends
    VerifiableRequestInterface,
    ValidableNotificationRequestInterface
{
}
