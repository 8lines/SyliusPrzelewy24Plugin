<?php

declare(strict_types=1);

namespace BitBag\SyliusPrzelewy24Plugin\Shared\Provider;

use Sylius\Component\Core\Model\OrderInterface;
use Sylius\Component\Core\Model\PaymentInterface;
use Sylius\Component\Payment\Model\PaymentRequestInterface;
use Webmozart\Assert\Assert;

final readonly class PaymentOrderProvider implements PaymentOrderProviderInterface
{

    public function provide(PaymentRequestInterface $paymentRequest): OrderInterface
    {
        /** @var PaymentInterface $payment */
        $payment = $paymentRequest->getPayment();

        /** @var OrderInterface $order */
        $order = $payment->getOrder();

        Assert::notNull(
            value: $order,
            message: 'SyliusOrder cannot be null',
        );

        return $order;
    }
}
