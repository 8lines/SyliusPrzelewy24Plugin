<?php

declare(strict_types=1);

namespace BitBag\SyliusPrzelewy24Plugin\Subscription\Entity;

use Sylius\Resource\Model\ResourceInterface;

interface RecurringOrderInterface extends ResourceInterface
{
    public function isRecurring(): bool;

    public function setRecurring(bool $recurring): void;

    public function getRecurringSequenceIndex(): ?int;

    public function setRecurringSequenceIndex(?int $recurringSequenceIndex): void;

    public function getSubscription(): ?SubscriptionInterface;

    public function setSubscription(SubscriptionInterface $subscription): void;

    public function getSyliusOrder(): ?RecurringSyliusOrderInterface;

    public function setSyliusOrder(RecurringSyliusOrderInterface $syliusOrder): void;
}
