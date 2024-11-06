<?php

declare(strict_types=1);

namespace BitBag\SyliusPrzelewy24Plugin\Subscription\Entity;

use Sylius\Component\Core\Model\ProductVariantInterface as BaseProductVariantInterface;

interface RecurringProductVariantInterface extends BaseProductVariantInterface
{
    public function getPrzelewy24ProductVariant(): Przelewy24ProductVariantInterface;

    public function setPrzelewy24ProductVariant(Przelewy24ProductVariantInterface $przelewy24ProductVariant): void;
}
