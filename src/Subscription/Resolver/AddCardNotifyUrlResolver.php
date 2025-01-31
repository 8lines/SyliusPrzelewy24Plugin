<?php

declare(strict_types=1);

namespace BitBag\SyliusPrzelewy24Plugin\Subscription\Resolver;

use BitBag\SyliusPrzelewy24Plugin\Shared\Resolver\NotifyUrlResolvableTransactionRequestInterface;
use BitBag\SyliusPrzelewy24Plugin\Shared\Resolver\TransactionNotifyUrlResolverInterface;
use BitBag\SyliusPrzelewy24Plugin\Subscription\Entity\AddCardRequestInterface;
use BitBag\SyliusPrzelewy24Plugin\Subscription\Payload\AddCardPayload;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Webmozart\Assert\Assert;

final readonly class AddCardNotifyUrlResolver implements TransactionNotifyUrlResolverInterface
{
    public function __construct(
        private UrlGeneratorInterface $urlGenerator,
    ) {
    }

    public function resolve(NotifyUrlResolvableTransactionRequestInterface $request): string
    {
        /** @var AddCardRequestInterface $request */

        Assert::isInstanceOf(
            value: $request,
            class: AddCardRequestInterface::class,
            message: 'Request must be instance of %2$s, but is %s.',
        );

        /** @var AddCardPayload $payload */
        $payload = $request->getTransactionPayload();

        return $this->urlGenerator->generate(
            name: 'bitbag_sylius_przelewy24_plugin_add_card_notify',
            parameters: ['hash' => $payload->notifyRequestHash()],
            referenceType: UrlGeneratorInterface::ABSOLUTE_URL,
        );
    }
}
