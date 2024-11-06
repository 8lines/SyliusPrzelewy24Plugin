<?php

declare(strict_types=1);

namespace BitBag\SyliusPrzelewy24Plugin\Subscription\Entity;

use Sylius\Component\Core\Model\OrderInterface;

class Przelewy24Subscription implements Przelewy24SubscriptionInterface
{
    public const INITIAL_SEQUENCE = 0;

    private int $id;

    private string $state;

    private ?Przelewy24CustomerInterface $owner;

    private ?OrderInterface $baseRecurringOrder;

    private ?\DateTimeImmutable $startsAt;

    private ?\DateTimeImmutable $endsAt;

    private Przelewy24SubscriptionConfiguration $configuration;

    private Przelewy24SubscriptionScheduleInterface $schedule;

    public function __construct()
    {
        $this->state = Przelewy24SubscriptionInterface::STATE_ACTIVE;
        $this->configuration = new Przelewy24SubscriptionConfiguration();
        $this->schedule = new Przelewy24SubscriptionSchedule();
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
        return Przelewy24SubscriptionInterface::STATE_ACTIVE === $this->state;
    }

    public function isCompleted(): bool
    {
        return Przelewy24SubscriptionInterface::STATE_COMPLETED === $this->state;
    }

    public function isAborted(): bool
    {
        return Przelewy24SubscriptionInterface::STATE_ABORTED === $this->state;
    }

    public function getOwner(): ?Przelewy24CustomerInterface
    {
        return $this->owner;
    }

    public function setOwner(?Przelewy24CustomerInterface $owner): void
    {
        $this->owner = $owner;
    }

    public function getBaseRecurringOrder(): ?RecurringOrderInterface
    {
        return $this->baseRecurringOrder;
    }

    public function setBaseRecurringOrder(?RecurringOrderInterface $baseRecurringOrder): void
    {
        $this->baseRecurringOrder = $baseRecurringOrder;
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

    public function getConfiguration(): Przelewy24SubscriptionConfiguration
    {
        return $this->configuration;
    }

    public function setConfiguration(Przelewy24SubscriptionConfiguration $configuration): void
    {
        $configuration->setSubscription($this);

        $this->configuration = $configuration;
    }

    public function getSchedule(): Przelewy24SubscriptionScheduleInterface
    {
        return $this->schedule;
    }

    public function setSchedule(Przelewy24SubscriptionScheduleInterface $schedule): void
    {
        $schedule->setSubscription($this);

        $this->schedule = $schedule;
    }
}
