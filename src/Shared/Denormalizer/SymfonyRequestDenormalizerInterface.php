<?php

declare(strict_types=1);

namespace BitBag\SyliusPrzelewy24Plugin\Shared\Denormalizer;

use Symfony\Component\HttpFoundation\Request;

interface SymfonyRequestDenormalizerInterface
{
    public function denormalize(array $httpRequest): ?Request;
}
