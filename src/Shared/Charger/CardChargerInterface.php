<?php

declare(strict_types=1);

namespace BitBag\SyliusPrzelewy24Plugin\Shared\Charger;

interface CardChargerInterface
{
    public function charge(CardChargeableRequestInterface $request): void;
}
