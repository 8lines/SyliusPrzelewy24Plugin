<?php

declare(strict_types=1);

namespace BitBag\SyliusPrzelewy24Plugin\Shared\Processor;

use BitBag\SyliusPrzelewy24Plugin\Shared\Entity\TransactionalPaymentRequestInterface;
use BitBag\SyliusPrzelewy24Plugin\Shared\StateResolver\PaymentStateResolverInterface;
use Webmozart\Assert\Assert;

final readonly class PaymentNotificationPaymentStateProcessor implements TransactionNotificationProcessorInterface
{
    public function __construct(
        private PaymentStateResolverInterface $paymentStateResolver,
    ) {
    }

    public function process(NotificationRequestInterface $request): void
    {
        /** @var TransactionalPaymentRequestInterface $request */

        Assert::isInstanceOf(
            value: $request,
            class: TransactionalPaymentRequestInterface::class,
            message: 'Invalid request type. Should be instance of %2$s, but is %s',
        );

        $this->paymentStateResolver->resolve(
            request: $request,
        );
    }
}
