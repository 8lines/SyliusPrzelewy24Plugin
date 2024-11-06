<?php

declare(strict_types=1);

namespace BitBag\SyliusPrzelewy24Plugin\Subscription\Action\Api;

use BitBag\SyliusPrzelewy24Plugin\Subscription\Request\Api\ChargeCardWith3ds;
use Payum\Core\Action\ActionInterface;
use Payum\Core\ApiAwareInterface;
use Payum\Core\ApiAwareTrait;
use Payum\Core\Bridge\Spl\ArrayObject;
use Payum\Core\Exception\RequestNotSupportedException;
use Payum\Core\Reply\HttpRedirect;
use Przelewy24\Przelewy24;

final class ChargeCardWith3dsAction implements ActionInterface, ApiAwareInterface
{
    use ApiAwareTrait;

    public function __construct()
    {
         $this->apiClass = Przelewy24::class;
    }

    public function execute($request): void
    {
        /** @var ChargeCardWith3ds $request */
        RequestNotSupportedException::assertSupports($this, $request);

        $model = ArrayObject::ensureArrayObject($request->getModel());
        $model->validateNotEmpty(['transactionToken']);

        /** @var Przelewy24 $api */
        $api = $this->api;

        $chargeCard = $api->cards()->chargeWith3ds($model['transactionToken']);
        
        throw new HttpRedirect($chargeCard->redirectUrl());
    }

    public function supports($request): bool
    {
        return $request instanceof ChargeCardWith3ds
            && $request->getModel() instanceof \ArrayAccess;
    }
}
