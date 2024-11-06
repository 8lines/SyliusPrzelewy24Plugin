<?php

declare(strict_types=1);

namespace BitBag\SyliusPrzelewy24Plugin\Subscription\Checker;

use BitBag\SyliusPrzelewy24Plugin\Subscription\Przelewy24SubscriptionGatewayFactory;
use Payum\Core\Payum;
use Payum\Core\Request\GetStatus;
use Sylius\Bundle\PayumBundle\Request\GetStatus;
use Sylius\Component\Core\Model\PaymentInterface;

final class IsPrzelewy24SubscriptionPaymentFailedChecker implements IsPrzelewy24SubscriptionPaymentFailedCheckerInterface
{
    public function __construct(
        private readonly Payum $payum,
    ) {
    }

    public function isFailed(PaymentInterface $payment): bool
    {
        $gateway = $this->payum->getGateway(
            name: Przelewy24SubscriptionGatewayFactory::GATEWAY_NAME,
        );

        $gateway->execute($status = new GetStatus($payment));

        return true === $status->isFailed();
    }
}
