<?php

declare(strict_types=1);

namespace BitBag\SyliusPrzelewy24Plugin\Subscription\Entity;

class Subscription implements SubscriptionInterface
{
    private int $id;

    private string $state;

    private ?SubscriberInterface $owner;

    private ?RecurringOrderInterface $baseOrder;

    private ?\DateTimeImmutable $startsAt;

    private ?\DateTimeImmutable $endsAt;

    private SubscriptionConfiguration $configuration;

    private SubscriptionScheduleInterface $schedule;

    public function __construct()
    {
        $this->state = SubscriptionInterface::STATE_ACTIVE;
        $this->configuration = new SubscriptionConfiguration();
        $this->schedule = new SubscriptionSchedule();
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getState(): string
    {
        return $this->state;
    }

    public function setState(string $state): void
    {
        $this->state = $state;
    }


    public function isActive(): bool
    {
        return SubscriptionInterface::STATE_ACTIVE === $this->state;
    }

    public function isCompleted(): bool
    {
        return SubscriptionInterface::STATE_COMPLETED === $this->state;
    }

    public function isAborted(): bool
    {
        return SubscriptionInterface::STATE_ABORTED === $this->state;
    }

    public function getOwner(): ?SubscriberInterface
    {
        return $this->owner;
    }

    public function setOwner(?SubscriberInterface $owner): void
    {
        $this->owner = $owner;
    }

    public function getBaseOrder(): ?RecurringOrderInterface
    {
        return $this->baseOrder;
    }

    public function setBaseOrder(?RecurringOrderInterface $baseOrder): void
    {
        $this->baseOrder = $baseOrder;
    }

    public function getStartsAt(): ?\DateTimeImmutable
    {
        return $this->startsAt;
    }

    public function setStartsAt(?\DateTimeImmutable $startsAt): void
    {
        $this->startsAt = $startsAt;
    }

    public function getEndsAt(): ?\DateTimeImmutable
    {
        return $this->endsAt;
    }

    public function setEndsAt(?\DateTimeImmutable $endsAt): void
    {
        $this->endsAt = $endsAt;
    }

    public function getConfiguration(): SubscriptionConfiguration
    {
        return $this->configuration;
    }

    public function setConfiguration(SubscriptionConfiguration $configuration): void
    {
        $configuration->setSubscription($this);

        $this->configuration = $configuration;
    }

    public function getSchedule(): SubscriptionScheduleInterface
    {
        return $this->schedule;
    }

    public function setSchedule(SubscriptionScheduleInterface $schedule): void
    {
        $schedule->setSubscription($this);

        $this->schedule = $schedule;
    }
}
