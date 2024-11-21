<?php

declare(strict_types=1);

namespace BitBag\SyliusPrzelewy24Plugin\Subscription\Entity;

use Doctrine\Common\Collections\Collection;
use Sylius\Resource\Model\ResourceInterface;

interface SubscriptionScheduleInterface extends ResourceInterface
{
    public function getCurrentSequence(): int;

    public function setCurrentSequence(int $currentSequence): void;

    public function incrementCurrentSequence(): void;

    /**
     * @return Collection<SubscriptionIntervalInterface>
     */
    public function getIntervals(): Collection;

    public function addInterval(SubscriptionIntervalInterface $interval): void;

    public function getIntervalBySequence(int $sequence): ?SubscriptionIntervalInterface;

    public function getInitialInterval(): ?SubscriptionIntervalInterface;

    public function getCurrentInterval(): ?SubscriptionIntervalInterface;

    public function getSubscription(): ?SubscriptionInterface;

    public function setSubscription(?SubscriptionInterface $subscription): void;
}
