<?php

declare(strict_types=1);

namespace BitBag\SyliusPrzelewy24Plugin\Shared\Resolver;

use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

final readonly class PaymentAfterUrlResolver implements TransactionAfterUrlResolverInterface
{
    public function __construct(
        private UrlGeneratorInterface $urlGenerator,
    ) {
    }

    public function resolve(AfterUrlResolvableTransactionRequestInterface $request): string
    {
        $redirectUrl = $request->getTransactionParameters()['redirectUrl'] ?? null;

        if (null !== $redirectUrl) {
            return $redirectUrl;
        }

        return $this->urlGenerator->generate(
            name: 'sylius_shop_order_thank_you',
            referenceType: UrlGeneratorInterface::ABSOLUTE_URL,
        );
    }
}
