<?php

declare(strict_types=1);

namespace BitBag\SyliusPrzelewy24Plugin\Shared\Processor;

use BitBag\SyliusPrzelewy24Plugin\Shared\Checker\TransactionNotificationValidityCheckerInterface;
use BitBag\SyliusPrzelewy24Plugin\Shared\StateResolver\PaymentStateResolverInterface;
use BitBag\SyliusPrzelewy24Plugin\Shared\Verifier\TransactionVerifierInterface;
use Sylius\Component\Payment\Model\PaymentRequestInterface;
use Webmozart\Assert\Assert;

final readonly class TransactionNotificationProcessor implements TransactionNotificationProcessorInterface
{
    public function __construct(
        private TransactionNotificationValidityCheckerInterface $compositeTransactionNotificationValidityChecker,
        private TransactionVerifierInterface $transactionVerifier,
        private PaymentStateResolverInterface $paymentStateResolver,
    ) {
    }

    public function process(PaymentRequestInterface $paymentRequest): void
    {
        Assert::true(
            value: $this->compositeTransactionNotificationValidityChecker->isValid($paymentRequest),
            message: 'Transaction notification is not authenticated.',
        );

        $this->transactionVerifier->verify(
            paymentRequest: $paymentRequest,
        );

        $this->paymentStateResolver->resolve(
            paymentRequest: $paymentRequest,
        );
    }
}
