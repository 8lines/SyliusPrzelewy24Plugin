<?php

declare(strict_types=1);

namespace BitBag\SyliusPrzelewy24Plugin\Shared\Creator;

use Sylius\Component\Payment\Model\PaymentRequestInterface;

interface TransactionCreatorInterface
{
    public function create(PaymentRequestInterface $paymentRequest): void;
}
