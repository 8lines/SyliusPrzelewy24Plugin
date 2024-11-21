<?php

declare(strict_types=1);

namespace BitBag\SyliusPrzelewy24Plugin\Subscription\Entity;

use Sylius\Component\Core\Model\PaymentInterface;

class SubscriptionInterval implements SubscriptionIntervalInterface
{
    private int $id;

    private string $state;

    private ?int $sequence;

    private ?\DateTimeImmutable $startsAt;

    private ?\DateTimeImmutable $endsAt;

    private int $failedPaymentAttempts;

    private ?RecurringOrderInterface $order;

    private ?SubscriptionSchedule $schedule;

    public function __construct()
    {
        $this->state = SubscriptionIntervalInterface::STATE_SCHEDULED;
        $this->failedPaymentAttempts = 0;
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

    public function isScheduled(): bool
    {
        return SubscriptionIntervalInterface::STATE_SCHEDULED === $this->state;
    }

    public function isPaid(): bool
    {
        return PaymentInterface::STATE_COMPLETED === $this->order?->getSyliusOrder()?->getLastPayment()?->getState();
    }

    public function isFulfilled(): bool
    {
        return SubscriptionIntervalInterface::STATE_FULFILLED === $this->state;
    }

    public function isCompleted(): bool
    {
        return SubscriptionIntervalInterface::STATE_COMPLETED === $this->state;
    }

    public function isAborted(): bool
    {
        return SubscriptionIntervalInterface::STATE_ABORTED === $this->state;
    }

    public function getSequence(): ?int
    {
        return $this->sequence;
    }

    public function setSequence(?int $sequence): void
    {
        $this->sequence = $sequence;
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

    public function getFailedPaymentAttempts(): int
    {
        return $this->failedPaymentAttempts;
    }

    public function setFailedPaymentAttempts(int $failedPaymentAttempts): void
    {
        $this->failedPaymentAttempts = $failedPaymentAttempts;
    }

    public function incrementFailedPaymentAttempts(): void
    {
        $this->failedPaymentAttempts++;
    }

    public function resetFailedPaymentAttempts(): void
    {
        $this->failedPaymentAttempts = 0;
    }

    public function getOrder(): ?RecurringOrderInterface
    {
        return $this->order;
    }

    public function setOrder(?RecurringOrderInterface $order): void
    {
        $this->order = $order;
    }

    public function getSchedule(): ?SubscriptionScheduleInterface
    {
        return $this->schedule;
    }

    public function setSchedule(?SubscriptionScheduleInterface $schedule): void
    {
        $this->schedule = $schedule;
    }
}
