<?php

declare(strict_types=1);

namespace BitBag\SyliusPrzelewy24Plugin\Shared\Assigner;

interface TransactionPayloadDataAssignerInterface
{
    public function assign(PayloadAssignableRequestInterface $request): void;
}
