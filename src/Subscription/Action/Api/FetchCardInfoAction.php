<?php

declare(strict_types=1);

namespace BitBag\SyliusPrzelewy24Plugin\Subscription\Action\Api;

use BitBag\SyliusPrzelewy24Plugin\Subscription\Request\Api\FetchCardInfo;
use Payum\Core\Action\ActionInterface;
use Payum\Core\ApiAwareInterface;
use Payum\Core\ApiAwareTrait;
use Payum\Core\Bridge\Spl\ArrayObject;
use Payum\Core\Exception\RequestNotSupportedException;
use Przelewy24\Przelewy24;

final class FetchCardInfoAction implements ActionInterface, ApiAwareInterface
{
    use ApiAwareTrait;

    public function __construct()
    {
        $this->apiClass = Przelewy24::class;
    }

    public function execute($request): void
    {
        /** @var FetchCardInfo $request */
        RequestNotSupportedException::assertSupports($this, $request);

        $model = ArrayObject::ensureArrayObject($request->getModel());
        $model->validateNotEmpty(['orderId']);

        /** @var Przelewy24 $api */
        $api = $this->api;

        $cardInfo = $api->cards()->info($model['orderId']);
        $request->setCardInfo($cardInfo);
    }

    public function supports($request): bool
    {
        return $request instanceof FetchCardInfo
            && $request->getModel() instanceof \ArrayAccess;
    }
}
