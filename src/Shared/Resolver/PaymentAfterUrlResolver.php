<?php

declare(strict_types=1);

namespace BitBag\SyliusPrzelewy24Plugin\Shared\Resolver;

use Sylius\Component\Payment\Model\PaymentRequestInterface;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

final readonly class PaymentAfterUrlResolver implements PaymentAfterUrlResolverInterface
{
    public function __construct(
        private UrlGeneratorInterface $urlGenerator,
    ) {
    }

    public function resolve(PaymentRequestInterface $paymentRequest): string
    {
        $redirectUrl = $paymentRequest->getPayload()['redirectUrl'] ?? null;

        if (null !== $redirectUrl) {
            return $redirectUrl;
        }

        return $this->urlGenerator->generate(
            name: 'sylius_shop_order_thank_you',
            referenceType: UrlGeneratorInterface::ABSOLUTE_URL,
        );
    }
}
