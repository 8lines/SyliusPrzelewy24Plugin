<?php

declare(strict_types=1);

namespace BitBag\SyliusPrzelewy24Plugin\Subscription\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;

class SubscriptionSchedule implements SubscriptionScheduleInterface
{
    private int $id;

    private int $currentSequence;

    /**
     * @var Collection<SubscriptionIntervalInterface>
     */
    private Collection $intervals;

    private ?SubscriptionInterface $subscription;

    public function __construct()
    {
        $this->currentSequence = SubscriptionInterface::INITIAL_SEQUENCE;
        $this->intervals = new ArrayCollection();
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getCurrentSequence(): int
    {
        return $this->currentSequence;
    }

    public function setCurrentSequence(int $currentSequence): void
    {
        $this->currentSequence = $currentSequence;
    }

    public function incrementCurrentSequence(): void
    {
        $this->currentSequence++;
    }

    public function getIntervals(): Collection
    {
        return $this->intervals;
    }

    public function addInterval(SubscriptionIntervalInterface $interval): void
    {
        $interval->setSchedule($this);
        $this->intervals->add($interval);
    }

    public function getIntervalBySequence(int $sequence): ?SubscriptionIntervalInterface
    {
        foreach ($this->intervals as $interval) {
            if ($interval->getSequence() === $sequence) {
                return $interval;
            }
        }

        return null;
    }

    public function getInitialInterval(): ?SubscriptionIntervalInterface
    {
        return $this->getIntervalBySequence(SubscriptionInterface::INITIAL_SEQUENCE);
    }

    public function getCurrentInterval(): ?SubscriptionIntervalInterface
    {
        return $this->getIntervalBySequence($this->currentSequence);
    }

    public function getSubscription(): ?SubscriptionInterface
    {
        return $this->subscription;
    }

    public function setSubscription(?SubscriptionInterface $subscription): void
    {
        $this->subscription = $subscription;
    }
}
