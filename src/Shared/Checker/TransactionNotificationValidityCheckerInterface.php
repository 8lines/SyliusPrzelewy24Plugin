<?php

declare(strict_types=1);

namespace BitBag\SyliusPrzelewy24Plugin\Shared\Checker;

interface TransactionNotificationValidityCheckerInterface
{
    public function isValid(ValidableNotificationRequestInterface $request): bool;
}
