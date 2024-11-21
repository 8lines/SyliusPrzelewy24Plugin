<?php

declare(strict_types=1);

namespace BitBag\SyliusPrzelewy24Plugin\Subscription\Resolver;

interface CardRefIdResolverInterface
{
    public function resolveFromTokenAndSubscriberId(
        string $token,
        int $subscriberId,
    );
}
