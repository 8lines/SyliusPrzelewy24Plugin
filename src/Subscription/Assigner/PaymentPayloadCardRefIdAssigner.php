<?php

declare(strict_types=1);

namespace BitBag\SyliusPrzelewy24Plugin\Subscription\Assigner;

use BitBag\SyliusPrzelewy24Plugin\Shared\Assigner\PaymentPayloadAssignerInterface;
use BitBag\SyliusPrzelewy24Plugin\Shared\Assigner\PaymentPayloadDataAssignerInterface;
use BitBag\SyliusPrzelewy24Plugin\Shared\Provider\PaymentOrderProviderInterface;
use BitBag\SyliusPrzelewy24Plugin\Shared\Provider\PaymentPayloadProviderInterface;
use BitBag\SyliusPrzelewy24Plugin\Subscription\Entity\SyliusCustomerAsSubscriberInterface;
use BitBag\SyliusPrzelewy24Plugin\Subscription\Resolver\CardRefIdResolverInterface;
use Sylius\Component\Payment\Model\PaymentRequestInterface;
use Webmozart\Assert\Assert;

final readonly class PaymentPayloadCardRefIdAssigner implements PaymentPayloadDataAssignerInterface
{
    public function __construct(
        private PaymentPayloadProviderInterface $paymentPayloadProvider,
        private PaymentPayloadAssignerInterface $paymentPayloadAssigner,
        private PaymentOrderProviderInterface $paymentOrderProvider,
        private CardRefIdResolverInterface $cardRefIdResolver,
    ) {
    }

    public function assign(PaymentRequestInterface $paymentRequest): void
    {
        $cardToken = $paymentRequest->getPayload()['cardToken'] ?? null;
        $cardRefId = $paymentRequest->getPayload()['cardRefId'] ?? null;

        if (null === $cardRefId && null === $cardToken) {
            return;
        }

        if (null !== $cardToken) {
            $order = $this->paymentOrderProvider->provide(
                paymentRequest: $paymentRequest,
            );

            /** @var SyliusCustomerAsSubscriberInterface $customer */
            $customer = $order->getCustomer();

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

        $payload = $this->paymentPayloadProvider->provideFromPaymentRequest(
            paymentRequest: $paymentRequest,
        );

        $payload->withCardRefId($cardRefId);

        $this->paymentPayloadAssigner->assign(
            paymentRequest: $paymentRequest,
            payload: $payload,
        );
    }
}
