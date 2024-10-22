<?php

/*
 * This file was created by developers working at BitBag
 * Do you need more information about us and what we do? Visit our https://bitbag.io website!
 * We are hiring developers from all over the world. Join us and start your new, exciting adventure and become part of us: https://bitbag.io/career
*/

declare(strict_types=1);

namespace BitBag\SyliusPrzelewy24Plugin\Action;

use BitBag\SyliusPrzelewy24Plugin\Request\Api\FetchTransaction;
use Payum\Core\Action\ActionInterface;
use Payum\Core\Bridge\Spl\ArrayObject;
use Payum\Core\Exception\RequestNotSupportedException;
use Payum\Core\GatewayAwareInterface;
use Payum\Core\GatewayAwareTrait;
use Payum\Core\Request\Sync;

final class SyncAction implements ActionInterface, GatewayAwareInterface
{
    use GatewayAwareTrait;

    public function execute($request): void
    {
        /** @var Sync $request */
        RequestNotSupportedException::assertSupports($this, $request);

        $model = ArrayObject::ensureArrayObject($request->getModel());
        $model->validateNotEmpty(['sessionId']);

        $this->gateway->execute($fetchTransaction = new FetchTransaction($model));

        $transaction = $fetchTransaction->getTransaction();

        $model['orderId'] = $transaction->orderId();
        $model['status'] = $transaction->status();
        $model['amount'] = $transaction->amount();
        $model['currency'] = $transaction->currency()->value;
        $model['email'] = $transaction->clientEmail();
        $model['description'] = $transaction->description();
        $model['client'] = $transaction->clientName();
        $model['address'] = $transaction->clientAddress();
        $model['city'] = $transaction->clientCity();
        $model['zip'] = $transaction->clientPostcode();
        $model['statement'] = \lcfirst($transaction->statement());
        $model['methodId'] = $transaction->paymentMethod();
    }

    public function supports($request): bool
    {
        return $request instanceof Sync
            && $request->getModel() instanceof \ArrayObject;
    }
}
