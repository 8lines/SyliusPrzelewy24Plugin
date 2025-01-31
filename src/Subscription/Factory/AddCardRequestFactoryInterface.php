<?php

declare(strict_types=1);

namespace BitBag\SyliusPrzelewy24Plugin\Subscription\Factory;

use BitBag\SyliusPrzelewy24Plugin\Subscription\Entity\AddCardRequestInterface;
use Sylius\Component\Core\Model\CustomerInterface;
use Sylius\Component\Core\Model\PaymentMethodInterface;
use Sylius\Resource\Factory\FactoryInterface;

/**
 * @extends FactoryInterface<AddCardRequestInterface>
 */
interface AddCardRequestFactoryInterface extends FactoryInterface
{
    public function createCaptureRequest(
        CustomerInterface $customer,
        PaymentMethodInterface $paymentMethod,
        #[\SensitiveParameter] mixed $parameters,
        #[\SensitiveParameter] array $httpRequest,
    ): AddCardRequestInterface;

    public function createNotifyRequest(AddCardRequestInterface $captureRequest): AddCardRequestInterface;
}
