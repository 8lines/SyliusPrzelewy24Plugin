<?php

declare(strict_types=1);

namespace BitBag\SyliusPrzelewy24Plugin\Shared\Interface;

interface HttpRequestAwareInterface
{
    public function getNormalizedHttpRequest(): array;
}
