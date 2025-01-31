<?php

declare(strict_types=1);

namespace BitBag\SyliusPrzelewy24Plugin\Subscription\Checker;

use BitBag\SyliusPrzelewy24Plugin\Shared\Entity\TransactionalPaymentRequestInterface;

interface IsPaymentOrderRecurringCheckerInterface
{
    public function isRecurring(TransactionalPaymentRequestInterface $request): bool;
}
