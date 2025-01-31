<?php

declare(strict_types=1);

namespace BitBag\SyliusPrzelewy24Plugin\Shared\Interface;

use Sylius\Component\Core\Model\AddressInterface;

interface BillingAddressAwareInterface
{
    public function getBillingAddress(): ?AddressInterface;
}
