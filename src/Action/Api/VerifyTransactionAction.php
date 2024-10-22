<?php

declare(strict_types=1);

namespace BitBag\SyliusPrzelewy24Plugin\Action\Api;

use BitBag\SyliusPrzelewy24Plugin\Request\Api\VerifyTransaction;
use Payum\Core\Action\ActionInterface;
use Payum\Core\ApiAwareInterface;
use Payum\Core\ApiAwareTrait;
use Payum\Core\Bridge\Spl\ArrayObject;
use Payum\Core\Exception\RequestNotSupportedException;
use Przelewy24\Enums\Currency;
use Przelewy24\Przelewy24;

final class VerifyTransactionAction implements ActionInterface, ApiAwareInterface
{
    use ApiAwareTrait;

    public function __construct()
    {
        $this->apiClass = Przelewy24::class;
    }

    public function execute($request): void
    {
        /** @var VerifyTransaction $request */
        RequestNotSupportedException::assertSupports($this, $request);

        $model = ArrayObject::ensureArrayObject($request->getModel());

        /** @var Przelewy24 $api */
        $api = $this->api;

        $api->transactions()->verify(
            sessionId: $model['sessionId'],
            orderId: $model['orderId'],
            amount: $model['amount'],
            currency: Currency::from($model['currency']),
        );
    }

    public function supports($request): bool
    {
        return $request instanceof VerifyTransaction
            && $request->getModel() instanceof \ArrayAccess;
    }
}
