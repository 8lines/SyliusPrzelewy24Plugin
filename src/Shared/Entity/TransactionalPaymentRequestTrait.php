<?php

declare(strict_types=1);

namespace BitBag\SyliusPrzelewy24Plugin\Shared\Entity;

use BitBag\SyliusPrzelewy24Plugin\Shared\Payload\PaymentPayload;
use BitBag\SyliusPrzelewy24Plugin\Shared\Payload\TransactionPayloadInterface;
use Sylius\Component\Core\Model\AddressInterface;
use Sylius\Component\Core\Model\CustomerInterface;
use Sylius\Component\Core\Model\OrderInterface;
use Sylius\Component\Core\Model\PaymentInterface;
use Sylius\Component\Core\Model\PaymentMethodInterface;

trait TransactionalPaymentRequestTrait
{
    public function getBillingAddress(): ?AddressInterface
    {
        return $this->getOrder()?->getBillingAddress();
    }

    public function getCustomer(): ?CustomerInterface
    {
        /** @var CustomerInterface|null $customer */
        $customer = $this->getOrder()?->getCustomer();

        return $customer;
    }

    public function getOrder(): ?OrderInterface
    {
        /** @var PaymentInterface $payment */
        $payment = $this->getPayment();

        return $payment->getOrder();
    }

    public function getNormalizedHttpRequest(): array
    {
        return $this->getPayload()['http_request'] ?? [];
    }

    public function getLocaleCode(): ?string
    {
        return $this->getOrder()?->getLocaleCode();
    }

    public function getCurrencyCode(): ?string
    {
        return $this->getOrder()?->getCurrencyCode();
    }

    public function getOrderTotal(): ?int
    {
        return $this->getOrder()?->getTotal();
    }

    public function getShippingTotal(): ?int
    {
        return $this->getOrder()?->getShippingTotal();
    }

    public function getTransactionPayload(): TransactionPayloadInterface
    {
        return PaymentPayload::fromArray(
            data: $this->getPayment()->getDetails(),
        );
    }

    public function setTransactionPayload(TransactionPayloadInterface $transactionPayload): void
    {
        $this->getPayment()->setDetails(
            details: $transactionPayload->toArray(),
        );
    }

    public function getPaymentMethod(): ?PaymentMethodInterface
    {
        /** @var PaymentInterface $payment */
        $payment = $this->getPayment();

        /** @var PaymentMethodInterface|null $paymentMethod */
        $paymentMethod = $payment->getMethod();

        return $paymentMethod;
    }

    public function getTransactionParameters(): mixed
    {
        return $this->getPayload();
    }

    public function getTransactionResponse(): mixed
    {
        return $this->getResponseData();
    }

    public function setTransactionResponse(#[\SensitiveParameter] mixed $response): void
    {
        $this->setResponseData($response);
    }
}
