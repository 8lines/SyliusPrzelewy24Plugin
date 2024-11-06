<?php

/*
 * This file was created by developers working at BitBag
 * Do you need more information about us and what we do? Visit our https://bitbag.io website!
 * We are hiring developers from all over the world. Join us and start your new, exciting adventure and become part of us: https://bitbag.io/career
*/

declare(strict_types=1);

namespace BitBag\SyliusPrzelewy24Plugin\Shared\Action;

use BitBag\SyliusPrzelewy24Plugin\Shared\Enum\Przelewy24TransactionStatus;
use BitBag\SyliusPrzelewy24Plugin\Shared\Request\FetchTransaction;
use Payum\Core\Action\ActionInterface;
use Payum\Core\Bridge\Spl\ArrayObject;
use Payum\Core\GatewayAwareInterface;
use Payum\Core\GatewayAwareTrait;
use Payum\Core\Request\Sync;

abstract class BaseSyncAction implements ActionInterface, GatewayAwareInterface
{
    use GatewayAwareTrait;

    protected function synchronizeTransaction(Sync $request): void
    {
        $model = ArrayObject::ensureArrayObject($request->getModel());
        $model->validateNotEmpty(['sessionId']);

        $fetchTransaction = new FetchTransaction($model);
        $this->gateway->execute($fetchTransaction);

        $transaction = $fetchTransaction->getTransaction();

        $model['orderId'] = $transaction->orderId();
        $model['status'] = Przelewy24TransactionStatus::fromSdkTransactionStatus($transaction->status());
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
