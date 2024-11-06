<?php

declare(strict_types=1);

namespace BitBag\SyliusPrzelewy24Plugin\Subscription\Entity;

use Sylius\Component\Core\Model\OrderInterface as BaseOrderInterface;

interface RecurringOrderInterface extends BaseOrderInterface
{
    public function getPrzelewy24Order(): Przelewy24OrderInterface;

    public function setPrzelewy24Order(Przelewy24OrderInterface $przelewy24Order): void;

    public function getPrzelewy24RecurringProduct(): ?Przelewy24ProductVariantInterface;
}
