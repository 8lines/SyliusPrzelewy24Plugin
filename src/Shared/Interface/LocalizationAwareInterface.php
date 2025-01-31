<?php

declare(strict_types=1);

namespace BitBag\SyliusPrzelewy24Plugin\Shared\Interface;

interface LocalizationAwareInterface
{
    public function getLocaleCode(): ?string;

    public function getCurrencyCode(): ?string;
}
