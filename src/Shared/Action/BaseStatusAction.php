<?php

/*
 * This file was created by developers working at BitBag
 * Do you need more information about us and what we do? Visit our https://bitbag.io website!
 * We are hiring developers from all over the world. Join us and start your new, exciting adventure and become part of us: https://bitbag.io/career
*/

declare(strict_types=1);

namespace BitBag\SyliusPrzelewy24Plugin\Shared\Action;

use BitBag\SyliusPrzelewy24Plugin\Shared\Enum\Przelewy24TransactionStatus;
use Payum\Core\Action\ActionInterface;
use Payum\Core\Bridge\Spl\ArrayObject;
use Payum\Core\Request\GetStatusInterface;

abstract class BaseStatusAction implements ActionInterface
{
    protected function resolveRequestStatus(GetStatusInterface $request): void
    {
        $model = ArrayObject::ensureArrayObject($request->getModel());
        $status = $model['status'] ?? null;

        match ($status) {
            null => $request->markNew(),
            Przelewy24TransactionStatus::NO_PAYMENT => $request->markPending(),
            Przelewy24TransactionStatus::PAYMENT_COMPLETED => $request->markCaptured(),
            Przelewy24TransactionStatus::PAYMENT_RETURNED => $request->markRefunded(),
            Przelewy24TransactionStatus::PAYMENT_FAILED => $request->markFailed(),
            default => $request->markUnknown(),
        };
    }

    public function supports($request): bool
    {
        return $request instanceof GetStatusInterface
            && $request->getModel() instanceof \ArrayAccess;
    }
}
