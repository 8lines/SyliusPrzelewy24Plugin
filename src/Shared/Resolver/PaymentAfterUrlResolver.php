<?php

declare(strict_types=1);

namespace BitBag\SyliusPrzelewy24Plugin\Shared\Resolver;

use BitBag\SyliusPrzelewy24Plugin\Shared\Provider\PaymentHttpRequestProviderInterface;
use Sylius\Component\Payment\Model\PaymentRequestInterface;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

final readonly class PaymentAfterUrlResolver implements PaymentAfterUrlResolverInterface
{
    public function __construct(
        private PaymentHttpRequestProviderInterface $paymentHttpRequestProvider,
        private UrlGeneratorInterface $urlGenerator,
    ) {
    }

    public function resolve(PaymentRequestInterface $paymentRequest): string
    {
        $httpRequest = $this->paymentHttpRequestProvider->provide(
            paymentRequest: $paymentRequest,
        );

        if (null !== $httpRequest && true === $httpRequest->headers->has('x-redirect-after')) {
            return $httpRequest->headers->get('x-redirect-after');
        }

        return $this->urlGenerator->generate(
            name: 'sylius_shop_order_thank_you',
            referenceType: UrlGeneratorInterface::ABSOLUTE_URL,
        );
    }
}
