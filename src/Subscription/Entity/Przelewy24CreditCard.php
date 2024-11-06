<?php

declare(strict_types=1);

namespace BitBag\SyliusPrzelewy24Plugin\Subscription\Entity;

class Przelewy24CreditCard implements Przelewy24CreditCardInterface
{
    private int $id;

    private ?string $cardMask;

    private ?string $cardDate;

    private ?string $cardRefId;

    private ?Przelewy24CustomerInterface $owner;

    public function getId(): int
    {
        return $this->id;
    }

    public function getCardMask(): ?string
    {
        return $this->cardMask;
    }

    public function setCardMask(?string $cardMask): void
    {
        $this->cardMask = $cardMask;
    }

    public function getCardDate(): ?string
    {
        return $this->cardDate;
    }

    public function setCardDate(?string $cardDate): void
    {
        $this->cardDate = $cardDate;
    }

    public function getCardRefId(): ?string
    {
        return $this->cardRefId;
    }

    public function setCardRefId(?string $cardRefId): void
    {
        $this->cardRefId = $cardRefId;
    }

    public function getOwner(): ?Przelewy24CustomerInterface
    {
        return $this->owner;
    }

    public function setOwner(?Przelewy24CustomerInterface $owner): void
    {
        $this->owner = $owner;
    }
}
