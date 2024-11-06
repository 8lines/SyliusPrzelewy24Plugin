<?php

/*
 * This file was created by developers working at BitBag
 * Do you need more information about us and what we do? Visit our https://bitbag.io website!
 * We are hiring developers from all over the world. Join us and start your new, exciting adventure and become part of us: https://bitbag.io/career
*/

declare(strict_types=1);

namespace BitBag\SyliusPrzelewy24Plugin\Shared\Action;

use Payum\Core\Action\ActionInterface;
use Payum\Core\Request\Convert;
use Sylius\Component\Core\Model\OrderInterface;
use Sylius\Component\Core\Model\OrderItemInterface;
use Sylius\Component\Core\Model\PaymentInterface;

abstract class BaseConvertPaymentAction implements ActionInterface
{
    protected function extractRequiredDataFromSyliusPayment(Convert $request): array
    {
        /** @var PaymentInterface $payment */
        $payment = $request->getSource();

        /** @var OrderInterface $order */
        $order = $payment->getOrder();

        $paymentData = $this->preparePaymentData($payment);
        $customerData = $this->prepareCustomerData($order);
        $cartData = $this->prepareCartData($order);

        return \array_merge($paymentData, $customerData, $cartData);
    }

    public function supports($request): bool
    {
        return $request instanceof Convert
            && $request->getSource() instanceof PaymentInterface
            && $request->getTo() === 'array';
    }

    private function preparePaymentData(PaymentInterface $payment): array
    {
        $paymentData = [];

        $paymentData['originAmount'] = $payment->getAmount();
        $paymentData['currency'] = $payment->getCurrencyCode();
        $paymentData['description'] = "#{$payment->getOrder()->getNumber()}"; // TODO
        $paymentData['status'] = null;

        return $paymentData;
    }

    private function prepareCustomerData(OrderInterface $order): array
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

    private function prepareCartData(OrderInterface $order): array
    {
        $cardData = [];

        $sellerId = (string) $order->getChannel()?->getId();
        $sellerCategory = $order->getChannel()?->getName();

        $cartItems = $order->getItems()->map(
            fn (OrderItemInterface $item): array => [
                'sellerId' => $sellerId,
                'sellerCategory' => $sellerCategory,
                'name' => $item->getProduct()->getName(),
                'description' => $item->getProduct()->getDescription(),
                'quantity' => $item->getQuantity(),
                'price' => $item->getUnitPrice(),
                'number' => (string) $item->getProduct()->getId(),
            ],
        );

        $cardData['cart'] = $cartItems->toArray();

        return $cardData;
    }
}
