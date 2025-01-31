<?php

declare(strict_types=1);

namespace BitBag\SyliusPrzelewy24Plugin\Shared\Assigner;

use BitBag\SyliusPrzelewy24Plugin\Shared\Resolver\AfterUrlResolvableTransactionRequestInterface;
use BitBag\SyliusPrzelewy24Plugin\Shared\Resolver\NotifyUrlResolvableTransactionRequestInterface;
use BitBag\SyliusPrzelewy24Plugin\Shared\Interface\PayloadAwareInterface;

interface PayloadAssignableRequestInterface extends
    PayloadAwareInterface,
    AfterUrlResolvableTransactionRequestInterface,
    NotifyUrlResolvableTransactionRequestInterface
{
}
