<?php

/*
 * This file was created by developers working at BitBag
 * Do you need more information about us and what we do? Visit our https://bitbag.io website!
 * We are hiring developers from all over the world. Join us and start your new, exciting adventure and become part of us: https://bitbag.io/career
*/

declare(strict_types=1);

namespace BitBag\SyliusPrzelewy24Plugin\Subscription;

use BitBag\SyliusPrzelewy24Plugin\Shared\Action\Api\AuthenticateNotificationAction;
use BitBag\SyliusPrzelewy24Plugin\Shared\Action\Api\FetchTransactionAction;
use BitBag\SyliusPrzelewy24Plugin\Shared\Action\Api\RegisterTransactionAction;
use BitBag\SyliusPrzelewy24Plugin\Shared\Action\Api\VerifyTransactionAction;
use BitBag\SyliusPrzelewy24Plugin\Shared\Przelewy24GatewayFactory;
use BitBag\SyliusPrzelewy24Plugin\Subscription\Action\Api\ChargeCardAction;
use BitBag\SyliusPrzelewy24Plugin\Subscription\Action\Api\ChargeCardWith3dsAction;
use BitBag\SyliusPrzelewy24Plugin\Subscription\Action\Api\FetchCardInfoAction;
use BitBag\SyliusPrzelewy24Plugin\Subscription\Action\Api\RegisterSubscriptionTransactionAction;
use BitBag\SyliusPrzelewy24Plugin\Subscription\Action\CaptureAction;
use BitBag\SyliusPrzelewy24Plugin\Subscription\Action\ConvertPaymentAction;
use BitBag\SyliusPrzelewy24Plugin\Subscription\Action\GetCreditCartTokenAction;
use BitBag\SyliusPrzelewy24Plugin\Subscription\Action\NotifyAction;
use BitBag\SyliusPrzelewy24Plugin\Subscription\Action\StatusAction;
use BitBag\SyliusPrzelewy24Plugin\Subscription\Action\SyncAction;
use Payum\Core\Bridge\Spl\ArrayObject;

final class Przelewy24SubscriptionGatewayFactory extends Przelewy24GatewayFactory
{
    public const GATEWAY_NAME = 'przelewy24_subscription';

    protected function populateConfig(ArrayObject $config): void
    {
        $config->defaults([
            'payum.factory_name' => self::GATEWAY_NAME,
            'payum.factory_title' => 'Przelewy24 Subscription',

            'payum.action.convert_payment' => new ConvertPaymentAction(),
            'payum.action.capture' => new CaptureAction(),
            'payum.action.notify' => new NotifyAction(),
            'payum.action.status' => new StatusAction(),
            'payum.action.sync' => new SyncAction(),
            'payum.action.get_credit_card_token' => new GetCreditCartTokenAction(),

            'payum.action.api.register_subscription_transaction' => new RegisterSubscriptionTransactionAction(),
            'payum.action.api.charge_card_with_3ds' => new ChargeCardWith3dsAction(),
            'payum.action.api.charge_card' => new ChargeCardAction(),
            'payum.action.api.fetch_card_info' => new FetchCardInfoAction(),
            'payum.action.api.register_transaction' => new RegisterTransactionAction(),
            'payum.action.api.authenticate_notification' => new AuthenticateNotificationAction(),
            'payum.action.api.verify_transaction' => new VerifyTransactionAction(),
            'payum.action.api.fetch_transaction' => new FetchTransactionAction(),
        ]);

        $this->initializeLogger($config);
        $this->initializeApi($config);
    }
}
