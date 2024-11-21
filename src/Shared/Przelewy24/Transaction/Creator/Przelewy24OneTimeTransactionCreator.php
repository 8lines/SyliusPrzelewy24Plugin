<?php

declare(strict_types=1);

namespace BitBag\SyliusPrzelewy24Plugin\Shared\Przelewy24\Transaction\Creator;

use BitBag\SyliusPrzelewy24Plugin\Shared\Creator\TransactionCreatorInterface;
use Sylius\Component\Payment\Model\PaymentRequestInterface;

final readonly class Przelewy24OneTimeTransactionCreator implements TransactionCreatorInterface
{
    use Przelewy24TransactionCreatorTrait;

    public function create(PaymentRequestInterface $paymentRequest): void
    {
        $this->registerTransaction($paymentRequest);
    }
}
