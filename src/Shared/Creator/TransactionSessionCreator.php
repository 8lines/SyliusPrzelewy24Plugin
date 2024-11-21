<?php

declare(strict_types=1);

namespace BitBag\SyliusPrzelewy24Plugin\Shared\Creator;

use BitBag\SyliusPrzelewy24Plugin\Shared\Assigner\PaymentPayloadDataAssignerInterface;
use Sylius\Component\Payment\Model\PaymentRequestInterface;

final readonly class TransactionSessionCreator implements TransactionCreatorInterface
{
    public function __construct(
        private PaymentPayloadDataAssignerInterface $compositeTransactionSessionAssigner,
    ) {
    }

    public function create(PaymentRequestInterface $paymentRequest): void
    {
        $this->compositeTransactionSessionAssigner->assign(
            paymentRequest: $paymentRequest,
        );
    }
}
