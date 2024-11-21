<?php

declare(strict_types=1);

namespace BitBag\SyliusPrzelewy24Plugin\OneTime\CommandHandler\Payment;

use BitBag\SyliusPrzelewy24Plugin\OneTime\Command\Payment\StatusPaymentRequest;
use BitBag\SyliusPrzelewy24Plugin\Shared\Processor\PaymentRequestProcessorInterface;
use BitBag\SyliusPrzelewy24Plugin\Shared\StateResolver\PaymentStateResolverInterface;
use Sylius\Bundle\PaymentBundle\Provider\PaymentRequestProviderInterface;
use Sylius\Component\Payment\Model\PaymentRequestInterface;

final readonly class StatusPaymentRequestHandler
{
    public function __construct(
        private PaymentRequestProviderInterface $paymentRequestProvider,
        private PaymentRequestProcessorInterface $paymentRequestProcessor,
        private PaymentStateResolverInterface $paymentStateResolver,
    ) {
    }

    public function __invoke(StatusPaymentRequest $statusPaymentRequest): void
    {
        $paymentRequest = $this->paymentRequestProvider->provide(
            command: $statusPaymentRequest,
        );

        $this->paymentRequestProcessor->process(
            paymentRequest: $paymentRequest,
            action: fn(PaymentRequestInterface $paymentRequest) => $this->paymentStateResolver->resolve($paymentRequest),
        );
    }
}
