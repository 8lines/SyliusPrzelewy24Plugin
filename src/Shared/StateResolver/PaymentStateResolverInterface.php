<?php

declare(strict_types=1);

namespace BitBag\SyliusPrzelewy24Plugin\Shared\StateResolver;

use BitBag\SyliusPrzelewy24Plugin\Shared\Entity\TransactionalPaymentRequestInterface;

interface PaymentStateResolverInterface
{
    public function resolve(TransactionalPaymentRequestInterface $request): void;
}
