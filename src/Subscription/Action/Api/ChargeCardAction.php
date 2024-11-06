<?php

declare(strict_types=1);

namespace BitBag\SyliusPrzelewy24Plugin\Subscription\Action\Api;

use BitBag\SyliusPrzelewy24Plugin\Shared\Enum\Przelewy24TransactionStatus;
use BitBag\SyliusPrzelewy24Plugin\Subscription\Request\Api\ChargeCard;
use Payum\Core\Action\ActionInterface;
use Payum\Core\ApiAwareInterface;
use Payum\Core\ApiAwareTrait;
use Payum\Core\Bridge\Spl\ArrayObject;
use Payum\Core\Exception\RequestNotSupportedException;
use Payum\Core\GatewayAwareInterface;
use Payum\Core\GatewayAwareTrait;
use Przelewy24\Przelewy24;

final class ChargeCardAction implements ActionInterface, ApiAwareInterface, GatewayAwareInterface
{
    use ApiAwareTrait;
    use GatewayAwareTrait;

    public function __construct()
    {
        $this->apiClass = Przelewy24::class;
    }

    public function execute($request): void
    {
        /** @var ChargeCard $request */
        RequestNotSupportedException::assertSupports($this, $request);

        $model = ArrayObject::ensureArrayObject($request->getModel());
        $model->validateNotEmpty(['transactionToken']);

        /** @var Przelewy24 $api */
        $api = $this->api;

        try {
            $api->cards()->charge($model['transactionToken']);
        } catch (\Exception $exception) {
            $model['status'] = Przelewy24TransactionStatus::PAYMENT_FAILED;
        }
    }

    public function supports($request): bool
    {
        return $request instanceof ChargeCard
            && $request->getModel() instanceof \ArrayAccess;
    }
}
