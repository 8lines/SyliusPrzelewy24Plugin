<?php

declare(strict_types=1);

namespace BitBag\SyliusPrzelewy24Plugin\Shared\Creator;

use BitBag\SyliusPrzelewy24Plugin\Shared\Assigner\TransactionPayloadDataAssignerInterface;

final readonly class TransactionSessionCreator implements TransactionCreatorInterface
{
    public function __construct(
        private TransactionPayloadDataAssignerInterface $compositeTransactionSessionAssigner,
    ) {
    }

    public function create(CreatableTransactionRequest $request): void
    {
        $this->compositeTransactionSessionAssigner->assign(
            request: $request,
        );
    }
}
