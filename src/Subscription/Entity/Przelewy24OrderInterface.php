<?php

declare(strict_types=1);

namespace BitBag\SyliusPrzelewy24Plugin\Subscription\Entity;

use Sylius\Resource\Model\ResourceInterface;

interface Przelewy24OrderInterface extends ResourceInterface
{
    public function isRecurring(): bool;

    public function setRecurring(bool $recurring): void;

    public function getRecurringSequenceIndex(): ?int;

    public function setRecurringSequenceIndex(?int $recurringSequenceIndex): void;

    public function getSubscription(): ?Przelewy24SubscriptionInterface;

    public function setSubscription(Przelewy24SubscriptionInterface $subscription): void;
}
