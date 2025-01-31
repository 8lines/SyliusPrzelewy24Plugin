<?php

declare(strict_types=1);

namespace BitBag\SyliusPrzelewy24Plugin\Subscription\Assigner;

use BitBag\SyliusPrzelewy24Plugin\Shared\Assigner\ResponseAssignableTransactionRequestInterface;

interface AddCardPayloadNotifyRequestHashAssignerInterface
{
    public function assign(
        ResponseAssignableTransactionRequestInterface $request,
        string $notifyRequestHash,
    ): void;
}
