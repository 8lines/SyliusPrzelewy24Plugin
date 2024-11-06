<?php

declare(strict_types=1);

namespace BitBag\SyliusPrzelewy24Plugin\Subscription\Entity;

use Sylius\Resource\Model\ResourceInterface;

interface Przelewy24SubscriptionInterface extends ResourceInterface
{
    public const STATE_ACTIVE = 'active';

    public const STATE_COMPLETED = 'completed';

    public const STATE_ABORTED = 'aborted';

    public function getState(): string;

    public function setState(string $state): void;

    public function isActive(): bool;

    public function isCompleted(): bool;

    public function isAborted(): bool;

    public function getOwner(): ?Przelewy24CustomerInterface;

    public function setOwner(?Przelewy24CustomerInterface $owner): void;

    public function getBaseRecurringOrder(): ?RecurringOrderInterface;

    public function setBaseRecurringOrder(?RecurringOrderInterface $baseRecurringOrder): void;

    public function getStartsAt(): ?\DateTimeImmutable;

    public function setStartsAt(?\DateTimeImmutable $startsAt): void;

    public function getEndsAt(): ?\DateTimeImmutable;

    public function setEndsAt(?\DateTimeImmutable $endsAt): void;

    public function getConfiguration(): Przelewy24SubscriptionConfiguration;

    public function setConfiguration(Przelewy24SubscriptionConfiguration $configuration): void;

    public function getSchedule(): Przelewy24SubscriptionScheduleInterface;

    public function setSchedule(Przelewy24SubscriptionScheduleInterface $schedule): void;
}
