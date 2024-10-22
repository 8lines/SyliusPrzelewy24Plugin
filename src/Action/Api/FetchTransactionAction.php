<?php

declare(strict_types=1);

namespace BitBag\SyliusPrzelewy24Plugin\Action\Api;

use BitBag\SyliusPrzelewy24Plugin\Request\Api\FetchTransaction;
use Payum\Core\Action\ActionInterface;
use Payum\Core\ApiAwareInterface;
use Payum\Core\ApiAwareTrait;
use Payum\Core\Bridge\Spl\ArrayObject;
use Payum\Core\Exception\RequestNotSupportedException;
use Przelewy24\Przelewy24;

final class FetchTransactionAction implements ActionInterface, ApiAwareInterface
{
    use ApiAwareTrait;

    public function __construct()
    {
        $this->apiClass = Przelewy24::class;
    }

    public function execute($request): void
    {
        /** @var FetchTransaction $request */
        RequestNotSupportedException::assertSupports($this, $request);

        $model = ArrayObject::ensureArrayObject($request->getModel());
        $model->validateNotEmpty(['sessionId']);

        /** @var Przelewy24 $api */
        $api = $this->api;

        $transaction = $api->transactions()->find($model['sessionId']);
        $request->setTransaction($transaction);
    }

    public function supports($request): bool
    {
        return $request instanceof FetchTransaction
            && $request->getModel() instanceof \ArrayAccess;
    }
}
