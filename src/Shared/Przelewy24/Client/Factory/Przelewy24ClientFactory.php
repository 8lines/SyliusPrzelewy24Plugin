<?php

declare(strict_types=1);

namespace BitBag\SyliusPrzelewy24Plugin\Shared\Przelewy24\Client\Factory;

use Przelewy24\Enums\Environment;
use Przelewy24\Przelewy24;
use Sylius\Component\Core\Model\PaymentMethodInterface;
use Webmozart\Assert\Assert;

final readonly class Przelewy24ClientFactory implements Przelewy24ClientFactoryInterface
{
    public function createFromPaymentMethod(PaymentMethodInterface $paymentMethod): Przelewy24
    {
        $gatewayConfig = $paymentMethod->getGatewayConfig();

        Assert::notNull(
            value: $gatewayConfig,
            message: 'Gateway config cannot be null.',
        );

        /** @var int $merchantId */
        $merchantId = (int) $gatewayConfig->getConfig()['merchant_id'] ?? null;

        Assert::notNull(
            value: $merchantId,
            message: 'Merchant ID cannot be null.',
        );

        /** @var string $reportsKey */
        $reportsKey = $gatewayConfig->getConfig()['reports_key'] ?? null;

        Assert::notNull(
            value: $reportsKey,
            message: 'Reports key cannot be null.',
        );

        /** @var string $crcKey */
        $crcKey = $gatewayConfig->getConfig()['crc_key'] ?? null;

        Assert::notNull(
            value: $crcKey,
            message: 'CRC key cannot be null.',
        );

        /** @var Environment $environment */
        $environment = $gatewayConfig->getConfig()['environment'] ?? null;

        Assert::notNull(
            value: $environment,
            message: 'Environment cannot be null.',
        );

        return new Przelewy24(
            merchantId: $merchantId,
            reportsKey: $reportsKey,
            crc: $crcKey,
            environment: $environment instanceof Environment ? $environment : Environment::tryFrom($environment),
        );
    }
}
