<?php

declare(strict_types=1);

namespace BitBag\SyliusPrzelewy24Plugin\Shared\Processor;

use Sylius\Component\Payment\Model\PaymentRequestInterface;

interface PaymentRequestProcessorInterface
{
    /**
     * @param callable<PaymentRequestInterface, void> $action
     */
    public function process(
        PaymentRequestInterface $paymentRequest,
        callable $action,
    ): void;
}
