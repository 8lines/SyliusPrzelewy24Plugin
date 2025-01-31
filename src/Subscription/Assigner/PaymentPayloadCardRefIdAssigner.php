<?php

declare(strict_types=1);

namespace BitBag\SyliusPrzelewy24Plugin\Subscription\Assigner;

use BitBag\SyliusPrzelewy24Plugin\Shared\Assigner\PayloadAssignableRequestInterface;
use BitBag\SyliusPrzelewy24Plugin\Shared\Assigner\TransactionPayloadDataAssignerInterface;
use BitBag\SyliusPrzelewy24Plugin\Shared\Entity\TransactionalPaymentRequestInterface;
use BitBag\SyliusPrzelewy24Plugin\Subscription\Entity\SyliusCustomerAsSubscriberInterface;
use BitBag\SyliusPrzelewy24Plugin\Subscription\Resolver\CardRefIdResolverInterface;
use Webmozart\Assert\Assert;

final readonly class PaymentPayloadCardRefIdAssigner implements TransactionPayloadDataAssignerInterface
{
    public function __construct(
        private CardRefIdResolverInterface $cardRefIdResolver,
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

        $cardToken = $request->getPayload()['cardToken'] ?? null;
        $cardRefId = $request->getPayload()['cardRefId'] ?? null;

        if (null === $cardRefId && null === $cardToken) {
            return;
        }

        if (null !== $cardToken) {
            /** @var SyliusCustomerAsSubscriberInterface $customer */
            $customer = $request->getCustomer();

            Assert::notNull(
                value: $customer,
                message: 'Order customer cannot be null',
            );

            $cardRefId = $this->cardRefIdResolver->resolveFromTokenAndSubscriberId(
                token: $cardToken,
                subscriberId: $customer->getPrzelewy24Subscriber()->getId(),
            );
        }

        if (null === $cardRefId) {
            return;
        }

        $payload = $request->getTransactionPayload();
        $payload->withCardRefId($cardRefId);

        $request->setTransactionPayload($payload);
    }
}
