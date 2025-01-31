<?php

declare(strict_types=1);

namespace BitBag\SyliusPrzelewy24Plugin\Subscription\Factory;

use BitBag\SyliusPrzelewy24Plugin\Subscription\Entity\AddCardRequestInterface;
use Sylius\Component\Core\Model\CustomerInterface;
use Sylius\Component\Core\Model\PaymentMethodInterface;
use Sylius\Resource\Factory\FactoryInterface;

final readonly class AddCardRequestFactory implements AddCardRequestFactoryInterface
{
    public function __construct(
        private FactoryInterface $decoratedFactory,
    ) {
    }

    public function createNew(): AddCardRequestInterface
    {
        return $this->decoratedFactory->createNew();
    }

    public function createCaptureRequest(
        CustomerInterface $customer,
        PaymentMethodInterface $paymentMethod,
        #[\SensitiveParameter] mixed $parameters,
        #[\SensitiveParameter] array $httpRequest,
    ): AddCardRequestInterface {
        $addCardRequest = $this->createNew();

        $parameters = \array_merge($parameters, $httpRequest);

        $addCardRequest->setAction(AddCardRequestInterface::ACTION_CAPTURE);
        $addCardRequest->setCustomer($customer);
        $addCardRequest->setPaymentMethod($paymentMethod);
        $addCardRequest->setParameters($parameters);

        return $addCardRequest;
    }

    public function createNotifyRequest(AddCardRequestInterface $captureRequest): AddCardRequestInterface
    {
        $addCardRequest = $this->createNew();

        $addCardRequest->setAction(AddCardRequestInterface::ACTION_NOTIFY);
        $addCardRequest->setCustomer($captureRequest->getCustomer());
        $addCardRequest->setPaymentMethod($captureRequest->getPaymentMethod());
        $addCardRequest->setPayload($captureRequest->getPayload());

        return $addCardRequest;
    }
}
