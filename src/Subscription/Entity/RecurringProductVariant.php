<?php

declare(strict_types=1);

namespace BitBag\SyliusPrzelewy24Plugin\Subscription\Entity;

class RecurringProductVariant implements RecurringProductVariantInterface
{
    private int $id;

    private bool $recurring = false;

    private ?int $recurringTimes;

    private ?int $recurringIntervalInDays;

    private ?RecurringSyliusProductVariantInterface $syliusProductVariant;

    public function getId(): int
    {
        return $this->id;
    }

    public function isRecurring(): bool
    {
        return $this->recurring;
    }

    public function setRecurring(bool $recurring): void
    {
        $this->recurring = $recurring;
    }

    public function getRecurringTimes(): ?int
    {
        return $this->recurringTimes;
    }

    public function setRecurringTimes(?int $recurringTimes): void
    {
        $this->recurringTimes = $recurringTimes;
    }

    public function getRecurringIntervalInDays(): ?int
    {
        return $this->recurringIntervalInDays;
    }

    public function setRecurringIntervalInDays(?int $recurringIntervalInDays): void
    {
        $this->recurringIntervalInDays = $recurringIntervalInDays;
    }

    public function getSyliusProductVariant(): ?RecurringSyliusProductVariantInterface
    {
        return $this->syliusProductVariant;
    }

    public function setSyliusProductVariant(RecurringSyliusProductVariantInterface $syliusProductVariant): void
    {
        $this->syliusProductVariant = $syliusProductVariant;
    }
}
