<?php

declare(strict_types=1);

namespace BitBag\SyliusPrzelewy24Plugin\Shared;

use Payum\Core\Bridge\Spl\ArrayObject;
use Payum\Core\GatewayFactory;
use Przelewy24\Enums\Environment;
use Przelewy24\Przelewy24;

abstract class Przelewy24GatewayFactory extends GatewayFactory
{
    protected function initializeApi(ArrayObject $config): void
    {
        if (false === \class_exists(Przelewy24::class)) {
            throw new \LogicException('You must install "mnastalski/przelewy24-php" library to use the Przelewy24 payment gateway.');
        }

        $config['payum.required_options'] = [
            'merchant_id',
            'reports_key',
            'crc_key',
            'environment',
        ];

        $config['payum.api'] = function (ArrayObject $config) {
            $config->validateNotEmpty($config['payum.required_options']);

            return new Przelewy24(
                merchantId: (int) $config['merchant_id'],
                reportsKey: $config['reports_key'],
                crc: $config['crc_key'],
                environment: Environment::from($config['environment']),
            );
        };
    }
}
