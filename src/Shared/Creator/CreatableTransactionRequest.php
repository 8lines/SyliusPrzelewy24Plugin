<?php

declare(strict_types=1);

namespace BitBag\SyliusPrzelewy24Plugin\Shared\Creator;

use BitBag\SyliusPrzelewy24Plugin\Shared\Assigner\PayloadAssignableRequestInterface;
use BitBag\SyliusPrzelewy24Plugin\Shared\Assigner\ResponseAssignableTransactionRequestInterface;
use BitBag\SyliusPrzelewy24Plugin\Shared\Interface\BillingAddressAwareInterface;
use BitBag\SyliusPrzelewy24Plugin\Shared\Interface\CustomerAwareInterface;
use BitBag\SyliusPrzelewy24Plugin\Shared\Interface\LocalizationAwareInterface;
use BitBag\SyliusPrzelewy24Plugin\Shared\Interface\OrderTotalAwareInterface;
use BitBag\SyliusPrzelewy24Plugin\Shared\Interface\PayloadAwareInterface;
use BitBag\SyliusPrzelewy24Plugin\Shared\Interface\PaymentMethodAwareInterface;

interface CreatableTransactionRequest extends
    PayloadAwareInterface,
    PaymentMethodAwareInterface,
    OrderTotalAwareInterface,
    LocalizationAwareInterface,
    CustomerAwareInterface,
    BillingAddressAwareInterface,
    PayloadAssignableRequestInterface,
    ResponseAssignableTransactionRequestInterface
{
}
