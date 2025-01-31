<?php

declare(strict_types=1);

namespace BitBag\SyliusPrzelewy24Plugin\Shared\Resolver;

use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Webmozart\Assert\Assert;

final readonly class PaymentNotifyUrlResolver implements TransactionNotifyUrlResolverInterface
{
    public function __construct(
        private UrlGeneratorInterface $urlGenerator,
    ) {
    }

    public function resolve(NotifyUrlResolvableTransactionRequestInterface $request): string
    {
        /** @var string $paymentMethodCode */
        $paymentMethodCode = $request->getPaymentMethod()->getCode();

        Assert::notNull(
            value: $paymentMethodCode,
            message: 'Payment method code cannot be null.',
        );

        return $this->urlGenerator->generate(
            name: 'sylius_payment_method_notify',
            parameters: [
                'code' => $paymentMethodCode,
                'hash' => $request->getHash()->toString(),
            ],
            referenceType: UrlGeneratorInterface::ABSOLUTE_URL,
        );
    }
}
