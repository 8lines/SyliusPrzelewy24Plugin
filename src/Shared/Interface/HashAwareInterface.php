<?php

declare(strict_types=1);

namespace BitBag\SyliusPrzelewy24Plugin\Shared\Interface;

use Symfony\Component\Uid\Uuid;

interface HashAwareInterface
{
    public function getHash(): ?Uuid;
}
