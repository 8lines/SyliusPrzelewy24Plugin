<?php

declare(strict_types=1);

namespace BitBag\SyliusPrzelewy24Plugin\Subscription\Entity;

use Sylius\Resource\Model\ResourceInterface;

interface SubscriptionConfigurationInterface extends ResourceInterface
{
    public function getRecurringTimes(): ?int;

    public function setRecurringTimes(?int $recurringTimes): void;

    public function getRecurringIntervalInDays(): ?int;

    public function setRecurringIntervalInDays(?int $recurringIntervalInDays): void;

    public function getHostName(): ?string;

    public function setHostName(?string $hostName): void;

    public function getLocaleCode(): ?string;

    public function setLocaleCode(?string $localeCode): void;

    public function getCard(): ?CardInterface;

    public function setCard(?CardInterface $card): void;

    public function getSubscription(): ?SubscriptionInterface;

    public function setSubscription(?SubscriptionInterface $subscription): void;
}
