<?php

declare(strict_types=1);

namespace BitBag\SyliusPrzelewy24Plugin\Subscription\Entity;

class Przelewy24Order implements Przelewy24OrderInterface
{
    private int $id;

    private bool $recurring = false;

    private ?int $recurringSequenceIndex;

    private ?Przelewy24SubscriptionInterface $subscription;

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

    public function getRecurringSequenceIndex(): ?int
    {
        return $this->recurringSequenceIndex;
    }

    public function setRecurringSequenceIndex(?int $recurringSequenceIndex): void
    {
        $this->recurringSequenceIndex = $recurringSequenceIndex;
    }

    public function getSubscription(): ?Przelewy24SubscriptionInterface
    {
        return $this->subscription;
    }

    public function setSubscription(Przelewy24SubscriptionInterface $subscription): void
    {
        $this->subscription = $subscription;
    }
}
