<?php

declare(strict_types=1);

namespace BitBag\SyliusPrzelewy24Plugin\Subscription\Entity;

use Sylius\Component\Core\Model\OrderInterface as BaseOrderInterface;

interface RecurringSyliusOrderInterface extends BaseOrderInterface
{
    public function getRecurringPrzelewy24Order(): RecurringOrderInterface;

    public function setRecurringPrzelewy24Order(RecurringOrderInterface $przelewy24Order): void;

    public function getRecurringPrzelewy24Product(): ?RecurringProductVariantInterface;
}
