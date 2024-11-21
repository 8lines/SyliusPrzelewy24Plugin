<?php

declare(strict_types=1);

namespace BitBag\SyliusPrzelewy24Plugin\Subscription\Entity;

use Sylius\Resource\Model\ResourceInterface;

interface CardInterface extends ResourceInterface
{
    public const CARD_TOKEN_LENGTH = 32;

    public function getToken(): ?string;

    public function setToken(?string $token): void;

    public function getMask(): ?string;

    public function setMask(?string $mask): void;

    public function getDate(): ?string;

    public function setDate(?string $date): void;

    public function getRefId(): ?string;

    public function setRefId(?string $refId): void;

    public function getOwner(): ?SubscriberInterface;

    public function setOwner(?SubscriberInterface $owner): void;
}
