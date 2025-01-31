<?php

declare(strict_types=1);

namespace BitBag\SyliusPrzelewy24Plugin\Shared\Resolver;

interface TransactionNotifyUrlResolverInterface
{
    public function resolve(NotifyUrlResolvableTransactionRequestInterface $request): string;
}
