<?php

declare(strict_types=1);

namespace BitBag\SyliusPrzelewy24Plugin\Subscription\Entity;

class Card implements CardInterface
{
    private int $id;

    private ?string $token;

    private ?string $mask;

    private ?string $date;

    private ?string $refId;

    private ?SubscriberInterface $owner;

    public function getId(): int
    {
        return $this->id;
    }

    public function getToken(): ?string
    {
        return $this->token;
    }

    public function setToken(?string $token): void
    {
        $this->token = $token;
    }

    public function getMask(): ?string
    {
        return $this->mask;
    }

    public function setMask(?string $mask): void
    {
        $this->mask = $mask;
    }

    public function getDate(): ?string
    {
        return $this->date;
    }

    public function setDate(?string $date): void
    {
        $this->date = $date;
    }

    public function getRefId(): ?string
    {
        return $this->refId;
    }

    public function setRefId(?string $refId): void
    {
        $this->refId = $refId;
    }

    public function getOwner(): ?SubscriberInterface
    {
        return $this->owner;
    }

    public function setOwner(?SubscriberInterface $owner): void
    {
        $this->owner = $owner;
    }
}
