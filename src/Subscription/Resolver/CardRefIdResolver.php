<?php

declare(strict_types=1);

namespace BitBag\SyliusPrzelewy24Plugin\Subscription\Resolver;

use BitBag\SyliusPrzelewy24Plugin\Subscription\Repository\CardRepositoryInterface;
use Webmozart\Assert\Assert;

final readonly class CardRefIdResolver implements CardRefIdResolverInterface
{
    public function __construct(
        private CardRepositoryInterface $cardRepository,
    ) {
    }

    public function resolveFromTokenAndSubscriberId(
        string $token,
        int $subscriberId,
    ): string {
        $card = $this->cardRepository->findByTokenAndSubscriberId(
            token: $token,
            subscriberId: $subscriberId,
        );

        Assert::notNull(
            value: $card,
            message: 'Card for given token and subscriberId not found',
        );

        Assert::notNull(
            value: $card->getRefId(),
            message: 'Card refId cannot be null',
        );

        return $card->getRefId();
    }
}
