<?php

/*
 * This file was created by developers working at BitBag
 * Do you need more information about us and what we do? Visit our https://bitbag.io website!
 * We are hiring developers from all over the world. Join us and start your new, exciting adventure and become part of us: https://bitbag.io/career
*/

declare(strict_types=1);

namespace BitBag\SyliusPrzelewy24Plugin\Action;

use BitBag\SyliusPrzelewy24Plugin\Request\Api\ValidateNotificationSign;
use BitBag\SyliusPrzelewy24Plugin\Request\Api\VerifyTransaction;
use Payum\Core\Action\ActionInterface;
use Payum\Core\Bridge\Spl\ArrayObject;
use Payum\Core\Exception\InvalidArgumentException;
use Payum\Core\Exception\RequestNotSupportedException;
use Payum\Core\GatewayAwareInterface;
use Payum\Core\GatewayAwareTrait;
use Payum\Core\Reply\HttpResponse;
use Payum\Core\Request\GetHttpRequest;
use Payum\Core\Request\Notify;
use Payum\Core\Request\Sync;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

final class NotifyAction implements ActionInterface, GatewayAwareInterface
{
    use GatewayAwareTrait;

    public function execute($request): void
    {
        /** @var Notify $request */
        RequestNotSupportedException::assertSupports($this, $request);

        $model = ArrayObject::ensureArrayObject($request->getModel());
        $model->validateNotEmpty(['sessionId']);

        $this->gateway->execute($httpRequest = new GetHttpRequest());

        if ($httpRequest->request['sessionId'] !== $model['sessionId']) {
            throw new NotFoundHttpException();
        }

        $this->gateway->execute(new Sync($model));
        $this->gateway->execute($validateNotificationSign = new ValidateNotificationSign($model));

        if (false === $validateNotificationSign->isValid()) {
            throw new InvalidArgumentException('Invalid sign');
        }

        $this->gateway->execute(new VerifyTransaction($model));
        $this->gateway->execute(new Sync($model));

        throw new HttpResponse('OK');
    }

    public function supports($request): bool
    {
        return $request instanceof Notify
            && $request->getModel() instanceof \ArrayAccess;
    }
}
