<?php

declare(strict_types=1);

namespace BitBag\SyliusPrzelewy24Plugin\Subscription\Entity;

class RecurringOrder implements RecurringOrderInterface
{
    private int $id;

    private bool $recurring = false;

    private ?int $recurringSequenceIndex;

    private ?SubscriptionInterface $subscription;

    private ?RecurringSyliusOrderInterface $syliusOrder;

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

    public function getSubscription(): ?SubscriptionInterface
    {
        return $this->subscription;
    }

    public function setSubscription(SubscriptionInterface $subscription): void
    {
        $this->subscription = $subscription;
    }

    public function getSyliusOrder(): ?RecurringSyliusOrderInterface
    {
        return $this->syliusOrder;
    }

    public function setSyliusOrder(RecurringSyliusOrderInterface $syliusOrder): void
    {
        $this->syliusOrder = $syliusOrder;
    }
}
