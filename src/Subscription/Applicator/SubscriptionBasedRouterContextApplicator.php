<?php

declare(strict_types=1);

namespace BitBag\SyliusPrzelewy24Plugin\Subscription\Applicator;

use BitBag\SyliusPrzelewy24Plugin\Subscription\Entity\SubscriptionInterface;
use Symfony\Component\Routing\RouterInterface;
use Webmozart\Assert\Assert;

final readonly class SubscriptionBasedRouterContextApplicator implements SubscriptionBasedRouterContextApplicatorInterface
{
    public function __construct(
        private RouterInterface $router,
    ) {
    }

    public function apply(SubscriptionInterface $subscription): void
    {
        $hostName = $subscription->getConfiguration()->getHostName();
        $localeCode = $subscription->getConfiguration()->getLocaleCode();

        Assert::notNull(
            value: $hostName,
            message: 'Subscription must have host name set',
        );

        Assert::notNull(
            value: $localeCode,
            message: 'Subscription must have locale set',
        );

        $routerContext = $this->router->getContext();

        $routerContext->setHost($hostName);
        $routerContext->setParameter('_locale', $localeCode);
    }
}
