<?php

declare(strict_types=1);

namespace BitBag\SyliusPrzelewy24Plugin\OneTime\Action\Api;

use BitBag\SyliusPrzelewy24Plugin\OneTime\Request\Api\RegisterOneTimeTransaction;
use BitBag\SyliusPrzelewy24Plugin\Shared\Request\RegisterTransaction;
use Payum\Core\Action\ActionInterface;
use Payum\Core\Bridge\Spl\ArrayObject;
use Payum\Core\Exception\InvalidArgumentException;
use Payum\Core\Exception\RequestNotSupportedException;
use Payum\Core\GatewayAwareInterface;
use Payum\Core\GatewayAwareTrait;
use Payum\Core\Reply\HttpRedirect;

final class RegisterOneTimeTransactionAction implements ActionInterface, GatewayAwareInterface
{
    use GatewayAwareTrait;

    public function execute($request): void
    {
        /** @var RegisterOneTimeTransaction $request */
        RequestNotSupportedException::assertSupports($this, $request);
        $model = ArrayObject::ensureArrayObject($request->getModel());

        $registerTransaction = new RegisterTransaction($model);
        $this->gateway->execute($registerTransaction);

        if (null !== $registerTransaction->getGatewayUrl()) {
            throw new HttpRedirect($registerTransaction->getGatewayUrl());
        }

        throw new InvalidArgumentException('The gateway URL could not be fetched');
    }

    public function supports($request): bool
    {
        return $request instanceof RegisterOneTimeTransaction
            && $request->getModel() instanceof \ArrayAccess;
    }
}
