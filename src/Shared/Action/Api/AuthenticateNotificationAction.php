<?php

declare(strict_types=1);

namespace BitBag\SyliusPrzelewy24Plugin\Shared\Action\Api;

use BitBag\SyliusPrzelewy24Plugin\Shared\Request\AuthenticateNotification;
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
use Webmozart\Assert\Assert;

final class AuthenticateNotificationAction implements ActionInterface, ApiAwareInterface, GatewayAwareInterface
{
    use ApiAwareTrait;
    use GatewayAwareTrait;

    public function __construct()
    {
        $this->apiClass = Przelewy24::class;
    }

    public function execute($request): void
    {
        /** @var AuthenticateNotification $request */
        RequestNotSupportedException::assertSupports($this, $request);

        $model = ArrayObject::ensureArrayObject($request->getModel());
        $model->validateNotEmpty($this->requiredModelFields());

        $amount = $model['amount'] ?? null;
        $originAmount = $model['originAmount'] ?? null;

        Assert::notNull(
            value: $amount,
            message: 'Amount is required to authenticate a notification.'
        );

        Assert::notNull(
            value: $originAmount,
            message: 'Origin amount is required to authenticate a notification.'
        );

        $getHttpRequest = new GetHttpRequest();
        $this->gateway->execute($getHttpRequest);

        /** @var Przelewy24 $api */
        $api = $this->api;

        $notification = $api->handleWebhook($getHttpRequest->request);

        if ($notification->sessionId() !== $model['sessionId']) {
            $request->setAuthenticated(false);
            return;
        }

        $notificationSignValid = $notification->isSignValid(
            sessionId: $model['sessionId'],
            amount: $amount,
            originAmount: $originAmount,
            orderId: $model['orderId'],
            methodId: $model['methodId'],
            statement: $model['statement'],
            currency: Currency::from($model['currency']),
        );

        if (false === $notificationSignValid) {
            $request->setAuthenticated(false);
            return;
        }

        $request->setAuthenticated(true);
    }

    public function supports($request): bool
    {
        return $request instanceof AuthenticateNotification
            && $request->getModel() instanceof \ArrayAccess;
    }

    private function requiredModelFields(): array
    {
        return [
            'sessionId',
            'orderId',
            'methodId',
            'statement',
            'currency',
        ];
    }
}
