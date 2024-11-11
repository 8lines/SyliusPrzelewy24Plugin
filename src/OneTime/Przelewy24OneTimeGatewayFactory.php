<?php

/*
 * This file was created by developers working at BitBag
 * Do you need more information about us and what we do? Visit our https://bitbag.io website!
 * We are hiring developers from all over the world. Join us and start your new, exciting adventure and become part of us: https://bitbag.io/career
*/

declare(strict_types=1);

namespace BitBag\SyliusPrzelewy24Plugin\OneTime;

use BitBag\SyliusPrzelewy24Plugin\OneTime\Action\Api\RegisterOneTimeTransactionAction;
use BitBag\SyliusPrzelewy24Plugin\OneTime\Action\CaptureAction;
use BitBag\SyliusPrzelewy24Plugin\OneTime\Action\ConvertPaymentAction;
use BitBag\SyliusPrzelewy24Plugin\OneTime\Action\NotifyAction;
use BitBag\SyliusPrzelewy24Plugin\OneTime\Action\StatusAction;
use BitBag\SyliusPrzelewy24Plugin\OneTime\Action\SyncAction;
use BitBag\SyliusPrzelewy24Plugin\Shared\Action\Api\AuthenticateNotificationAction;
use BitBag\SyliusPrzelewy24Plugin\Shared\Action\Api\FetchTransactionAction;
use BitBag\SyliusPrzelewy24Plugin\Shared\Action\Api\RegisterTransactionAction;
use BitBag\SyliusPrzelewy24Plugin\Shared\Action\Api\VerifyTransactionAction;
use BitBag\SyliusPrzelewy24Plugin\Shared\Przelewy24GatewayFactory;
use Payum\Core\Bridge\Spl\ArrayObject;

final class Przelewy24OneTimeGatewayFactory extends Przelewy24GatewayFactory
{
    public const GATEWAY_NAME = 'przelewy24_one_time';

    protected function populateConfig(ArrayObject $config): void
    {
        $config->defaults([
            'payum.factory_name' => self::GATEWAY_NAME,
            'payum.factory_title' => 'Przelewy24 One Time',

            'payum.action.convert_payment' => new ConvertPaymentAction(),
            'payum.action.capture' => new CaptureAction(),
            'payum.action.notify' => new NotifyAction(),
            'payum.action.status' => new StatusAction(),
            'payum.action.sync' => new SyncAction(),

            'payum.action.api.register_one_time_transaction' => new RegisterOneTimeTransactionAction(),
            'payum.action.api.register_transaction' => new RegisterTransactionAction(),
            'payum.action.api.authenticate_notification' => new AuthenticateNotificationAction(),
            'payum.action.api.verify_transaction' => new VerifyTransactionAction(),
            'payum.action.api.fetch_transaction' => new FetchTransactionAction(),
        ]);

        $this->initializeLogger($config);
        $this->initializeApi($config);
    }
}
