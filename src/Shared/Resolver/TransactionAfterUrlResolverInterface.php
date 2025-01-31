<?php

declare(strict_types=1);

namespace BitBag\SyliusPrzelewy24Plugin\Shared\Resolver;

interface TransactionAfterUrlResolverInterface
{
    public function resolve(AfterUrlResolvableTransactionRequestInterface $request): string;
}
