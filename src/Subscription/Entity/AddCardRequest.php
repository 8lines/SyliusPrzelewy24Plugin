<?php

declare(strict_types=1);

namespace BitBag\SyliusPrzelewy24Plugin\Subscription\Entity;

use BitBag\SyliusPrzelewy24Plugin\Shared\Payload\TransactionPayloadInterface;
use BitBag\SyliusPrzelewy24Plugin\Subscription\Payload\AddCardPayload;
use Sylius\Component\Core\Model\AddressInterface;
use Sylius\Component\Core\Model\CustomerInterface;
use Sylius\Component\Core\Model\PaymentMethodInterface;
use Symfony\Component\Uid\Uuid;

class AddCardRequest implements AddCardRequestInterface
{
    private ?Uuid $hash;

    private string $action;

    private string $state;

    private CustomerInterface $customer;

    private PaymentMethodInterface $paymentMethod;

    private mixed $parameters = null;

    private mixed $payload = null;

    private mixed $response = null;

    public function __construct()
    {
        $this->state = AddCardRequestInterface::STATE_NEW;
    }

    public function getId(): ?string
    {
        return $this->hash?->__toString();
    }

    public function getHash(): ?Uuid
    {
        return $this->hash;
    }

    public function getAction(): string
    {
        return $this->action;
    }

    public function setAction(string $action): void
    {
        $this->action = $action;
    }

    public function getState(): string
    {
        return $this->state;
    }

    public function setState(string $state): void
    {
        $this->state = $state;
    }

    public function getCustomer(): CustomerInterface
    {
        return $this->customer;
    }

    public function setCustomer(CustomerInterface $customer): void
    {
        $this->customer = $customer;
    }

    public function getPaymentMethod(): PaymentMethodInterface
    {
        return $this->paymentMethod;
    }

    public function setPaymentMethod(PaymentMethodInterface $paymentMethod): void
    {
        $this->paymentMethod = $paymentMethod;
    }

    public function getParameters(): mixed
    {
        return $this->parameters;
    }

    public function setParameters(#[\SensitiveParameter] mixed $parameters): void
    {
        $this->parameters = $parameters;
    }

    public function getPayload(): mixed
    {
        return $this->payload;
    }

    public function setPayload(#[\SensitiveParameter] mixed $payload): void
    {
        $this->payload = $payload;
    }

    public function getResponse(): mixed
    {
        return $this->response;
    }

    public function setResponse(#[\SensitiveParameter] mixed $response): void
    {
        $this->response = $response;
    }

    public function getBillingAddress(): ?AddressInterface
    {
        return null;
    }

    public function getNormalizedHttpRequest(): array
    {
        return $this->parameters['http_request'] ?? [];
    }

    public function getLocaleCode(): ?string
    {
        return AddCardRequestInterface::REQUEST_LOCALE_CODE;
    }

    public function getCurrencyCode(): ?string
    {
        return AddCardRequestInterface::REQUEST_CURRENCY_CODE;
    }

    public function getOrderTotal(): ?int
    {
        return AddCardRequestInterface::REQUEST_ORDER_TOTAL;
    }

    public function getShippingTotal(): ?int
    {
        return null;
    }

    public function getTransactionPayload(): TransactionPayloadInterface
    {
        return AddCardPayload::fromArray(
            data: $this->getPayload(),
        );
    }

    public function setTransactionPayload(TransactionPayloadInterface $transactionPayload): void
    {
        $this->setPayload(
            payload: $transactionPayload->toArray(),
        );
    }

    public function getTransactionParameters(): mixed
    {
        return $this->getParameters();
    }

    public function getTransactionResponse(): mixed
    {
        return $this->getResponse();
    }

    public function setTransactionResponse(#[\SensitiveParameter] mixed $response): void
    {
        $this->setResponse($response);
    }
}
