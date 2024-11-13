<?php

declare(strict_types=1);

namespace BitBag\SyliusPrzelewy24Plugin\Subscription\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;

class Przelewy24SubscriptionSchedule implements Przelewy24SubscriptionScheduleInterface
{
    private int $id;

    private int $currentSequence = 0;

    /**
     * @var Collection<Przelewy24SubscriptionScheduleIntervalInterface>
     */
    private Collection $intervals;

    private ?Przelewy24SubscriptionInterface $subscription;

    public function __construct()
    {
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

    public function addInterval(Przelewy24SubscriptionScheduleIntervalInterface $interval): void
    {
        $interval->setSchedule($this);
        $this->intervals->add($interval);
    }

    public function getIntervalBySequence(int $sequence): ?Przelewy24SubscriptionScheduleIntervalInterface
    {
        foreach ($this->intervals as $interval) {
            if ($interval->getSequence() === $sequence) {
                return $interval;
            }
        }

        return null;
    }

    public function getInitialInterval(): ?Przelewy24SubscriptionScheduleIntervalInterface
    {
        return $this->getIntervalBySequence(Przelewy24Subscription::INITIAL_SEQUENCE);
    }

    public function getCurrentInterval(): ?Przelewy24SubscriptionScheduleIntervalInterface
    {
        return $this->getIntervalBySequence($this->currentSequence);
    }

    public function getSubscription(): ?Przelewy24SubscriptionInterface
    {
        return $this->subscription;
    }

    public function setSubscription(?Przelewy24SubscriptionInterface $subscription): void
    {
        $this->subscription = $subscription;
    }
}
