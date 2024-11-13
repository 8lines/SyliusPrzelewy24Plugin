<?php

declare(strict_types=1);

namespace BitBag\SyliusPrzelewy24Plugin\Subscription\Entity;

class Przelewy24SubscriptionConfiguration implements Przelewy24SubscriptionConfigurationInterface
{
    private int $id;

    private ?int $recurringTimes;

    private ?int $recurringIntervalInDays;

    private ?string $hostName;

    private ?string $localeCode;

    private ?Przelewy24CreditCardInterface $creditCard;

    private ?Przelewy24Subscription $subscription;

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

    public function getCreditCard(): ?Przelewy24CreditCardInterface
    {
        return $this->creditCard;
    }

    public function setCreditCard(?Przelewy24CreditCardInterface $creditCard): void
    {
        $this->creditCard = $creditCard;
    }

    public function getSubscription(): ?Przelewy24SubscriptionInterface
    {
        return $this->subscription;
    }

    public function setSubscription(?Przelewy24SubscriptionInterface $subscription): void
    {
        $this->subscription = $subscription;
    }
}
