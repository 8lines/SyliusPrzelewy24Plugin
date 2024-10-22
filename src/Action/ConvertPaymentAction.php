<?php

/*
 * This file was created by developers working at BitBag
 * Do you need more information about us and what we do? Visit our https://bitbag.io website!
 * We are hiring developers from all over the world. Join us and start your new, exciting adventure and become part of us: https://bitbag.io/career
*/

declare(strict_types=1);

namespace BitBag\SyliusPrzelewy24Plugin\Action;

use Payum\Core\Action\ActionInterface;
use Payum\Core\Exception\RequestNotSupportedException;
use Payum\Core\Request\Convert;
use Sylius\Bundle\PayumBundle\Provider\PaymentDescriptionProviderInterface;
use Sylius\Component\Core\Model\OrderInterface;
use Sylius\Component\Core\Model\OrderItemInterface;
use Sylius\Component\Core\Model\PaymentInterface;

final class ConvertPaymentAction implements ActionInterface
{
    private PaymentDescriptionProviderInterface $paymentDescriptionProvider;

    public function __construct(PaymentDescriptionProviderInterface $paymentDescriptionProvider)
    {
        $this->paymentDescriptionProvider = $paymentDescriptionProvider;
    }

    public function execute($request): void
    {
        /** @var Convert $request */
        RequestNotSupportedException::assertSupports($this, $request);

        /** @var PaymentInterface $payment */
        $payment = $request->getSource();

        /** @var OrderInterface $order */
        $order = $payment->getOrder();

        $paymentData = $this->getPaymentData($payment);
        $customerData = $this->getCustomerData($order);

        $shoppingList = ['cart' => $this->getShoppingList($order)];

        $request->setResult(
            \array_merge($paymentData, $customerData, $shoppingList),
        );
    }

    public function supports($request): bool
    {
        return $request instanceof Convert
            && $request->getSource() instanceof PaymentInterface
            && 'array' === $request->getTo();
    }

    private function getPaymentData(PaymentInterface $payment): array
    {
        $paymentData = [];

        $paymentData['originAmount'] = $payment->getAmount();
        $paymentData['currency'] = $payment->getCurrencyCode();
        $paymentData['description'] = $this->paymentDescriptionProvider->getPaymentDescription($payment);

        return $paymentData;
    }

    private function getCustomerData(OrderInterface $order): array
    {
        $customerData = [];

        $customerData['shipping'] = $order->getShippingTotal();
        $customerData['language'] = \Locale::parseLocale($order->getLocaleCode())['language'];

        if (null !== $customer = $order->getCustomer()) {
            $customerData['email'] = $customer->getEmail();
        }

        if (null !== $address = $order->getShippingAddress()) {
            $customerData['address'] = $address->getStreet();
            $customerData['zip'] = $address->getPostcode();
            $customerData['country'] = $address->getCountryCode();
            $customerData['phone'] = $address->getPhoneNumber();
            $customerData['city'] = $address->getCity();
            $customerData['client'] = $address->getFullName();
        }

        return $customerData;
    }

    private function getShoppingList(OrderInterface $order): array
    {
        $sellerId = (string) $order->getChannel()?->getId();
        $sellerCategory = $order->getChannel()?->getName();

        return \array_map(
            callback: fn (OrderItemInterface $item): array => [
                'sellerId' => $sellerId,
                'sellerCategory' => $sellerCategory,
                'name' => $item->getProduct()->getName(),
                'description' => $item->getProduct()->getDescription(),
                'quantity' => $item->getQuantity(),
                'price' => $item->getUnitPrice(),
                'number' => (string) $item->getProduct()->getId(),
            ],
            array: $order->getItems()->toArray(),
        );
    }
}
