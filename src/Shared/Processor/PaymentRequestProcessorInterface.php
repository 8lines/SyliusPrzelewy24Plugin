<?php

declare(strict_types=1);

namespace BitBag\SyliusPrzelewy24Plugin\Shared\Processor;

use BitBag\SyliusPrzelewy24Plugin\Shared\Entity\TransactionalPaymentRequestInterface;

interface PaymentRequestProcessorInterface
{
    /**
     * @param callable<TransactionalPaymentRequestInterface, void> $action
     */
    public function process(
        TransactionalPaymentRequestInterface $request,
        callable $action,
    ): void;
}
