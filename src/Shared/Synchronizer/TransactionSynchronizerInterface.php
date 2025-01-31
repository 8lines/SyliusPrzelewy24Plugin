<?php

declare(strict_types=1);

namespace BitBag\SyliusPrzelewy24Plugin\Shared\Synchronizer;

interface TransactionSynchronizerInterface
{
    public function synchronize(SynchronizableRequestInterface $request): void;
}
