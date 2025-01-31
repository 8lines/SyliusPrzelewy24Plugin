<?php

declare(strict_types=1);

namespace BitBag\SyliusPrzelewy24Plugin\Subscription\Resolver;

use BitBag\SyliusPrzelewy24Plugin\Shared\Resolver\AfterUrlResolvableTransactionRequestInterface;
use BitBag\SyliusPrzelewy24Plugin\Shared\Resolver\TransactionAfterUrlResolverInterface;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

final readonly class AddCardAfterUrlResolver implements TransactionAfterUrlResolverInterface
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
            name: 'sylius_shop_homepage',
            referenceType: UrlGeneratorInterface::ABSOLUTE_URL,
        );
    }
}
