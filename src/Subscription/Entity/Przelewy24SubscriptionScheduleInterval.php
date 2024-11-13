<?php

declare(strict_types=1);

namespace BitBag\SyliusPrzelewy24Plugin\Subscription\Entity;

use Sylius\Component\Core\Model\OrderInterface;
use Sylius\Component\Core\Model\PaymentInterface;

class Przelewy24SubscriptionScheduleInterval implements Przelewy24SubscriptionScheduleIntervalInterface
{
    private int $id;

    private string $state;

    private ?int $sequence;

    private ?\DateTimeImmutable $startsAt;

    private ?\DateTimeImmutable $endsAt;

    private int $failedPaymentAttempts;

    private ?Przelewy24OrderInterface $order;

    private ?Przelewy24SubscriptionSchedule $schedule;

    public function __construct()
    {
        $this->state = Przelewy24SubscriptionScheduleIntervalInterface::STATE_SCHEDULED;
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
        return Przelewy24SubscriptionScheduleIntervalInterface::STATE_SCHEDULED === $this->state;
    }

    public function isPaid(): bool
    {
        return PaymentInterface::STATE_COMPLETED === $this->order?->getSyliusOrder()?->getLastPayment()?->getState();
    }

    public function isFulfilled(): bool
    {
        return Przelewy24SubscriptionScheduleIntervalInterface::STATE_FULFILLED === $this->state;
    }

    public function isCompleted(): bool
    {
        return Przelewy24SubscriptionScheduleIntervalInterface::STATE_COMPLETED === $this->state;
    }

    public function isAborted(): bool
    {
        return Przelewy24SubscriptionScheduleIntervalInterface::STATE_ABORTED === $this->state;
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

    public function getOrder(): ?Przelewy24OrderInterface
    {
        return $this->order;
    }

    public function setOrder(?Przelewy24OrderInterface $order): void
    {
        $this->order = $order;
    }

    public function getSchedule(): ?Przelewy24SubscriptionScheduleInterface
    {
        return $this->schedule;
    }

    public function setSchedule(?Przelewy24SubscriptionScheduleInterface $schedule): void
    {
        $this->schedule = $schedule;
    }
}
