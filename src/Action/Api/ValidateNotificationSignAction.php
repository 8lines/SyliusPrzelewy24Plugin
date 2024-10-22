<?php

declare(strict_types=1);

namespace BitBag\SyliusPrzelewy24Plugin\Action\Api;

use BitBag\SyliusPrzelewy24Plugin\Request\Api\ValidateNotificationSign;
use Payum\Core\Action\ActionInterface;
use Payum\Core\ApiAwareInterface;
use Payum\Core\ApiAwareTrait;
use Payum\Core\Bridge\Spl\ArrayObject;
use Payum\Core\Exception\RequestNotSupportedException;
use Payum\Core\GatewayAwareInterface;
use Payum\Core\GatewayAwareTrait;
use Payum\Core\Request\GetHttpRequest;
use Przelewy24\Enums\Currency;
use Przelewy24\Przelewy24;

final class ValidateNotificationSignAction implements ActionInterface, GatewayAwareInterface, ApiAwareInterface
{
    use GatewayAwareTrait;
    use ApiAwareTrait;

    public function __construct()
    {
        $this->apiClass = Przelewy24::class;
    }

    public function execute($request): void
    {
        /** @var ValidateNotificationSign $request */
        RequestNotSupportedException::assertSupports($this, $request);

        $model = ArrayObject::ensureArrayObject($request->getModel());
        $model->validateNotEmpty($this->requiredModelFields());

        $this->gateway->execute($httpRequest = new GetHttpRequest());

        /** @var Przelewy24 $api */
        $api = $this->api;

        $notification = $api->handleWebhook($httpRequest->request);

        $valid = $notification->isSignValid(
            sessionId: $model['sessionId'],
            amount: $model['amount'],
            originAmount: $model['originAmount'],
            orderId: $model['orderId'],
            methodId: $model['methodId'],
            statement: $model['statement'],
            currency: Currency::from($model['currency']),
        );

        $request->setValid($valid);
    }

    public function supports($request): bool
    {
        return $request instanceof ValidateNotificationSign
            && $request->getModel() instanceof \ArrayAccess;
    }

    private function requiredModelFields(): array
    {
        return [
            'sessionId',
            'amount',
            'originAmount',
            'orderId',
            'methodId',
            'statement',
            'currency',
        ];
    }
}
