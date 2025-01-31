<?php

declare(strict_types=1);

namespace BitBag\SyliusPrzelewy24Plugin\Subscription\Assigner;

use BitBag\SyliusPrzelewy24Plugin\Shared\Assigner\PayloadAssignableRequestInterface;
use BitBag\SyliusPrzelewy24Plugin\Shared\Assigner\TransactionPayloadDataAssignerInterface;
use BitBag\SyliusPrzelewy24Plugin\Shared\Entity\TransactionalPaymentRequestInterface;
use BitBag\SyliusPrzelewy24Plugin\Shared\Payload\PaymentPayload;
use BitBag\SyliusPrzelewy24Plugin\Subscription\Checker\IsPaymentOrderRecurringCheckerInterface;
use Webmozart\Assert\Assert;

final readonly class PaymentPayloadRecurringAssigner implements TransactionPayloadDataAssignerInterface
{
    public function __construct(
        private IsPaymentOrderRecurringCheckerInterface $isPaymentOrderRecurringChecker,
    ) {
    }

    public function assign(PayloadAssignableRequestInterface $request): void
    {
        /** @var TransactionalPaymentRequestInterface $request */

        Assert::isInstanceOf(
            value: $request,
            class: TransactionalPaymentRequestInterface::class,
            message: 'Invalid request type %s, expected %s',
        );

        $recurring = $this->isPaymentOrderRecurringChecker->isRecurring(
            request: $request,
        );

        /** @var PaymentPayload $payload */
        $payload = $request->getTransactionPayload();

        Assert::isInstanceOf(
            value: $payload,
            class: PaymentPayload::class,
            message: 'Invalid payload type %s, expected %s',
        );

        $payload->withRecurring($recurring);

        $request->setTransactionPayload($payload);
    }
}
