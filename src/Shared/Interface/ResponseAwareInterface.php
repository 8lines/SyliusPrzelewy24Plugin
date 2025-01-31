<?php

declare(strict_types=1);

namespace BitBag\SyliusPrzelewy24Plugin\Shared\Interface;

interface ResponseAwareInterface
{
    public function getTransactionResponse(): mixed;

    public function setTransactionResponse(#[\SensitiveParameter] mixed $response): void;
}
