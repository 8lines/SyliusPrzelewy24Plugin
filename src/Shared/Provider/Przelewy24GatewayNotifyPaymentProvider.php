<?php

declare(strict_types=1);

namespace BitBag\SyliusPrzelewy24Plugin\Shared\Provider;

use BitBag\SyliusPrzelewy24Plugin\OneTime\Przelewy24OneTimeGateway;
use BitBag\SyliusPrzelewy24Plugin\Subscription\Przelewy24SubscriptionGateway;
use Sylius\Bundle\PaymentBundle\Provider\NotifyPaymentProviderInterface;
use Sylius\Component\Payment\Model\PaymentInterface;
use Sylius\Component\Payment\Model\PaymentMethodInterface;
use Sylius\Component\Payment\Model\PaymentRequestInterface;
use Sylius\Component\Payment\Repository\PaymentRequestRepositoryInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Uid\Uuid;

final readonly class Przelewy24GatewayNotifyPaymentProvider implements NotifyPaymentProviderInterface
{
    public function __construct(
        private PaymentRequestRepositoryInterface $paymentRequestRepository,
    ) {
    }

    public function supports(
        Request $request,
        PaymentMethodInterface $paymentMethod,
    ): bool {
        return Przelewy24OneTimeGateway::GATEWAY_NAME === $paymentMethod->getGatewayConfig()?->getFactoryName()
            || Przelewy24SubscriptionGateway::GATEWAY_NAME === $paymentMethod->getGatewayConfig()?->getFactoryName();
    }

    public function getPayment(
        Request $request,
        PaymentMethodInterface $paymentMethod,
    ): PaymentInterface {
        /** @var string $hash */
        $hash = $request->query->get('hash');

        if (null === $hash) {
            throw new \LogicException('No hash provided in the request.');
        }

        /** @var PaymentRequestInterface $paymentRequest */
        $paymentRequest = $this->paymentRequestRepository->findOneBy([
            'hash' => Uuid::fromString($hash),
        ]);

        if (null === $paymentRequest) {
            throw new \LogicException(\sprintf('No payment request found with hash "%s".', $hash));
        }

        return $paymentRequest->getPayment();
    }
}
