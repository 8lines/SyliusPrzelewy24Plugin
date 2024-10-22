<?php

/*
 * This file was created by developers working at BitBag
 * Do you need more information about us and what we do? Visit our https://bitbag.io website!
 * We are hiring developers from all over the world. Join us and start your new, exciting adventure and become part of us: https://bitbag.io/career
*/

declare(strict_types=1);

namespace BitBag\SyliusPrzelewy24Plugin;

use Payum\Core\Bridge\Spl\ArrayObject;
use Payum\Core\GatewayFactory;
use Przelewy24\Przelewy24;

final class Przelewy24GatewayFactory extends GatewayFactory
{
    protected function populateConfig(ArrayObject $config): void
    {
        if (false === \class_exists(Przelewy24::class)) {
            throw new \LogicException('You must install "mnastalski/przelewy24-php" library to use the Przelewy24 payment gateway.');
        }

        $config->defaults([
            'payum.factory_name' => 'przelewy24',
            'payum.factory_title' => 'Przelewy24',
        ]);

        if (false === (bool) $config['payum.api']) {
            $config['payum.default_options'] = [
                'crc' => '',
                'reports_key' => '',
                'merchant_id' => '',
                'live' => false,
            ];

            $config->defaults($config['payum.default_options']);

            $config['payum.required_options'] = [
                'crc',
                'reports_key',
                'merchant_id',
            ];

            $config['payum.api'] = function (ArrayObject $config) {
                $config->validateNotEmpty($config['payum.required_options']);

                return new Przelewy24(
                    merchantId: (int) $config['merchant_id'],
                    reportsKey: $config['reports_key'],
                    crc: $config['crc'],
                    isLive: (bool) $config['live'],
                );
            };
        }
    }
}
