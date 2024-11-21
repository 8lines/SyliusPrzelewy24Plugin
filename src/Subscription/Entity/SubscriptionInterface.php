<?php

declare(strict_types=1);

namespace BitBag\SyliusPrzelewy24Plugin\Subscription\Entity;

use Sylius\Resource\Model\ResourceInterface;

interface SubscriptionInterface extends ResourceInterface
{
    public const INITIAL_SEQUENCE = 0;

    public const STATE_ACTIVE = 'active';

    public const STATE_COMPLETED = 'completed';

    public const STATE_ABORTED = 'aborted';

    public function getState(): string;

    public function setState(string $state): void;

    public function isActive(): bool;

    public function isCompleted(): bool;

    public function isAborted(): bool;

    public function getOwner(): ?SubscriberInterface;

    public function setOwner(?SubscriberInterface $owner): void;

    public function getBaseOrder(): ?RecurringOrderInterface;

    public function setBaseOrder(?RecurringOrderInterface $baseOrder): void;

    public function getStartsAt(): ?\DateTimeImmutable;

    public function setStartsAt(?\DateTimeImmutable $startsAt): void;

    public function getEndsAt(): ?\DateTimeImmutable;

    public function setEndsAt(?\DateTimeImmutable $endsAt): void;

    public function getConfiguration(): SubscriptionConfiguration;

    public function setConfiguration(SubscriptionConfiguration $configuration): void;

    public function getSchedule(): SubscriptionScheduleInterface;

    public function setSchedule(SubscriptionScheduleInterface $schedule): void;
}
