<?php

declare(strict_types=1);

namespace BitBag\SyliusPrzelewy24Plugin\Subscription\Action;

use BitBag\SyliusPrzelewy24Plugin\Shared\Action\BaseSyncAction;
use BitBag\SyliusPrzelewy24Plugin\Subscription\Request\Api\FetchCardInfo;
use Payum\Core\Bridge\Spl\ArrayObject;
use Payum\Core\Exception\RequestNotSupportedException;
use Payum\Core\Request\Sync;

final class SyncAction extends BaseSyncAction
{
    public function execute($request): void
    {
        /** @var Sync $request */
        RequestNotSupportedException::assertSupports($this, $request);

        $this->synchronizeTransaction($request);
        $this->synchronizeCardInfo($request);
    }

    private function synchronizeCardInfo(Sync $request): void
    {
        $model = ArrayObject::ensureArrayObject($request->getModel());

        $fetchCardInfo = new FetchCardInfo($model);
        $this->gateway->execute($fetchCardInfo);

        $cardInfo = $fetchCardInfo->getCardInfo();
        $model['cardRefId'] = $cardInfo->refId();
    }
}
