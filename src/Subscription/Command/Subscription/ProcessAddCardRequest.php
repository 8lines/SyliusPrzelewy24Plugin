<?php

declare(strict_types=1);

namespace BitBag\SyliusPrzelewy24Plugin\Subscription\Command\Subscription;

final readonly class ProcessAddCardRequest
{
    public function __construct(
        private string $hash,
    ) {
    }

    public function getHash(): string
    {
        return $this->hash;
    }
}
