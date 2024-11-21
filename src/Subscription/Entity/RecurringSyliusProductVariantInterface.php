<?php

declare(strict_types=1);

namespace BitBag\SyliusPrzelewy24Plugin\Subscription\Entity;

use Sylius\Component\Core\Model\ProductVariantInterface as BaseProductVariantInterface;

interface RecurringSyliusProductVariantInterface extends BaseProductVariantInterface
{
    public function getRecurringPrzelewy24ProductVariant(): RecurringProductVariantInterface;

    public function setRecurringPrzelewy24ProductVariant(RecurringProductVariantInterface $przelewy24ProductVariant): void;
}
