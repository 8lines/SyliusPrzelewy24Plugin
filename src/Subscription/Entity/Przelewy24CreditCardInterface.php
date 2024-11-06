<?php

declare(strict_types=1);

namespace BitBag\SyliusPrzelewy24Plugin\Subscription\Entity;

use Sylius\Resource\Model\ResourceInterface;

interface Przelewy24CreditCardInterface extends ResourceInterface
{
    public function getCardMask(): ?string;

    public function setCardMask(?string $cardMask): void;

    public function getCardDate(): ?string;

    public function setCardDate(?string $cardDate): void;

    public function getCardRefId(): ?string;

    public function setCardRefId(?string $cardRefId): void;

    public function getOwner(): ?Przelewy24CustomerInterface;

    public function setOwner(?Przelewy24CustomerInterface $owner): void;
}
