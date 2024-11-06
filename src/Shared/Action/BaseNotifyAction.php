<?php

declare(strict_types=1);

namespace BitBag\SyliusPrzelewy24Plugin\Shared\Action;

use BitBag\SyliusPrzelewy24Plugin\Shared\Request\AuthenticateNotification;
use BitBag\SyliusPrzelewy24Plugin\Shared\Request\VerifyTransaction;
use Payum\Core\Action\ActionInterface;
use Payum\Core\Bridge\Spl\ArrayObject;
use Payum\Core\Exception\InvalidArgumentException;
use Payum\Core\GatewayAwareInterface;
use Payum\Core\GatewayAwareTrait;
use Payum\Core\Reply\HttpResponse;
use Payum\Core\Request\Notify;
use Payum\Core\Request\Sync;

abstract class BaseNotifyAction implements ActionInterface, GatewayAwareInterface
{
    use GatewayAwareTrait;

    protected function ensureNotificationAuthenticated(Notify $request): void
    {
        $model = ArrayObject::ensureArrayObject($request->getModel());

        $this->gateway->execute(new Sync($model));

        $authenticateNotificationAction = new AuthenticateNotification($model);
        $this->gateway->execute($authenticateNotificationAction);

        if (false === $authenticateNotificationAction->isAuthenticated()) {
            throw new InvalidArgumentException('The notification is not authenticated');
        }
    }

    protected function verifyTransaction(Notify $request): void
    {
        $model = ArrayObject::ensureArrayObject($request->getModel());

        $this->gateway->execute(new VerifyTransaction($model));
        $this->gateway->execute(new Sync($model));
    }

    protected function returnPositiveResponse(): void
    {
        throw new HttpResponse('OK');
    }

    public function supports($request): bool
    {
        return $request instanceof Notify
            && $request->getModel() instanceof \ArrayAccess;
    }
}
