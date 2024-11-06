<?php

declare(strict_types=1);

namespace BitBag\SyliusPrzelewy24Plugin\Subscription\Entity;

use Sylius\Resource\Model\ResourceInterface;

interface Przelewy24SubscriptionConfigurationInterface extends ResourceInterface
{
    public function getRecurringTimes(): ?int;

    public function setRecurringTimes(?int $recurringTimes): void;

    public function getRecurringIntervalInDays(): ?int;

    public function setRecurringIntervalInDays(?int $recurringIntervalInDays): void;

    public function getHostName(): ?string;

    public function setHostName(?string $hostName): void;

    public function getCreditCard(): ?Przelewy24CreditCardInterface;

    public function setCreditCard(?Przelewy24CreditCardInterface $creditCard): void;

    public function getSubscription(): ?Przelewy24SubscriptionInterface;

    public function setSubscription(?Przelewy24SubscriptionInterface $subscription): void;
}
