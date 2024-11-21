<?php

declare(strict_types=1);

namespace BitBag\SyliusPrzelewy24Plugin\Subscription\Entity;

use Sylius\Resource\Model\ResourceInterface;

interface SubscriptionIntervalInterface extends ResourceInterface
{
    public const STATE_SCHEDULED = 'scheduled';

    public const STATE_AWAITING_PAYMENT = 'awaiting_payment';

    public const STATE_FULFILLED = 'fulfilled';

    public const STATE_COMPLETED = 'completed';

    public const STATE_ABORTED = 'aborted';

    public function getState(): string;

    public function setState(string $state): void;

    public function isScheduled(): bool;

    public function isPaid(): bool;

    public function isFulfilled(): bool;

    public function isCompleted(): bool;

    public function isAborted(): bool;

    public function getSequence(): ?int;

    public function setSequence(?int $sequence): void;

    public function getStartsAt(): ?\DateTimeImmutable;

    public function setStartsAt(?\DateTimeImmutable $startsAt): void;

    public function getEndsAt(): ?\DateTimeImmutable;

    public function setEndsAt(?\DateTimeImmutable $endsAt): void;

    public function getFailedPaymentAttempts(): int;

    public function setFailedPaymentAttempts(int $failedPaymentAttempts): void;

    public function incrementFailedPaymentAttempts(): void;

    public function resetFailedPaymentAttempts(): void;

    public function getOrder(): ?RecurringOrderInterface;

    public function setOrder(?RecurringOrderInterface $order): void;

    public function getSchedule(): ?SubscriptionScheduleInterface;

    public function setSchedule(?SubscriptionScheduleInterface $schedule): void;
}
