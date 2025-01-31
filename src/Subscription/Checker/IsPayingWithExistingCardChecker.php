<?php

declare(strict_types=1);

namespace BitBag\SyliusPrzelewy24Plugin\Subscription\Checker;

use BitBag\SyliusPrzelewy24Plugin\Shared\Entity\TransactionalPaymentRequestInterface;

final readonly class IsPayingWithExistingCardChecker implements IsPayingWithExistingCardCheckerInterface
{
    public function isPayingWithExistingCard(TransactionalPaymentRequestInterface $request): bool
    {
        return null !== $request->getTransactionPayload()->cardRefId();
    }
}
