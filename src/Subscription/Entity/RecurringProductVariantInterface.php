<?php

declare(strict_types=1);

namespace BitBag\SyliusPrzelewy24Plugin\Subscription\Entity;

use Sylius\Resource\Model\ResourceInterface;

interface RecurringProductVariantInterface extends ResourceInterface
{
    public function isRecurring(): bool;

    public function setRecurring(bool $recurring): void;

    public function getRecurringTimes(): ?int;

    public function setRecurringTimes(?int $recurringTimes): void;

    public function getRecurringIntervalInDays(): ?int;

    public function setRecurringIntervalInDays(?int $recurringIntervalInDays): void;

    public function getSyliusProductVariant(): ?RecurringSyliusProductVariantInterface;

    public function setSyliusProductVariant(RecurringSyliusProductVariantInterface $syliusProductVariant): void;
}
