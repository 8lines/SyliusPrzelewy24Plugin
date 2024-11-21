<?php

declare(strict_types=1);

namespace BitBag\SyliusPrzelewy24Plugin\Subscription\Entity;

class SubscriptionConfiguration implements SubscriptionConfigurationInterface
{
    private int $id;

    private ?int $recurringTimes;

    private ?int $recurringIntervalInDays;

    private ?string $hostName;

    private ?string $localeCode;

    private ?CardInterface $card;

    private ?Subscription $subscription;

    public function getId(): int
    {
        return $this->id;
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

    public function getHostName(): ?string
    {
        return $this->hostName;
    }

    public function setHostName(?string $hostName): void
    {
        $this->hostName = $hostName;
    }

    public function getLocaleCode(): ?string
    {
        return $this->localeCode;
    }

    public function setLocaleCode(?string $localeCode): void
    {
        $this->localeCode = $localeCode;
    }

    public function getCard(): ?CardInterface
    {
        return $this->card;
    }

    public function setCard(?CardInterface $card): void
    {
        $this->card = $card;
    }

    public function getSubscription(): ?SubscriptionInterface
    {
        return $this->subscription;
    }

    public function setSubscription(?SubscriptionInterface $subscription): void
    {
        $this->subscription = $subscription;
    }
}
