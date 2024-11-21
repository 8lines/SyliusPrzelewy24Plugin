<?php

declare(strict_types=1);

namespace BitBag\SyliusPrzelewy24Plugin\Shared\Przelewy24\Transaction\Creator;

use BitBag\SyliusPrzelewy24Plugin\Shared\Assigner\PaymentPayloadTransactionTokenAssignerInterface;
use BitBag\SyliusPrzelewy24Plugin\Shared\Assigner\PaymentResponseGatewayUrlAssignerInterface;
use BitBag\SyliusPrzelewy24Plugin\Shared\Provider\PaymentApiClientProviderInterface;
use BitBag\SyliusPrzelewy24Plugin\Shared\Provider\PaymentOrderProviderInterface;
use BitBag\SyliusPrzelewy24Plugin\Shared\Provider\PaymentPayloadProviderInterface;
use Przelewy24\Api\Requests\Items\CartItem;
use Przelewy24\Enums\Country;
use Przelewy24\Enums\Currency;
use Przelewy24\Enums\Language;
use Przelewy24\Enums\TransactionChannel;
use Przelewy24\Przelewy24;
use Sylius\Bundle\PayumBundle\Provider\PaymentDescriptionProviderInterface;
use Sylius\Component\Core\Model\AddressInterface;
use Sylius\Component\Core\Model\CustomerInterface;
use Sylius\Component\Core\Model\OrderInterface;
use Sylius\Component\Core\Model\OrderItemInterface;
use Sylius\Component\Core\Model\PaymentInterface;
use Sylius\Component\Payment\Model\PaymentRequestInterface;

trait Przelewy24TransactionCreatorTrait
{
    public function __construct(
        protected readonly PaymentPayloadProviderInterface $paymentPayloadProvider,
        protected readonly PaymentOrderProviderInterface $paymentOrderProvider,
        protected readonly PaymentApiClientProviderInterface $paymentApiClientProvider,
        protected readonly PaymentDescriptionProviderInterface $paymentDescriptionProvider,
        protected readonly PaymentPayloadTransactionTokenAssignerInterface $paymentPayloadTransactionTokenAssigner,
        protected readonly PaymentResponseGatewayUrlAssignerInterface $paymentResponseGatewayUrlAssigner,
    ) {
    }

    protected function registerTransaction(
        PaymentRequestInterface $paymentRequest,
        ?TransactionChannel $channel = null,
        ?string $methodRefId = null,
    ): void {
        $payload = $this->paymentPayloadProvider->provideFromPaymentRequest(
            paymentRequest: $paymentRequest,
        );

        $payload->validateNotNull([
            'sessionId',
            'notifyUrl',
            'afterUrl',
        ]);

        /** @var PaymentInterface $payment */
        $payment = $paymentRequest->getPayment();

        $order = $this->paymentOrderProvider->provide(
            paymentRequest: $paymentRequest,
        );

        /** @var CustomerInterface|null $customer */
        $customer = $order->getCustomer();

        /** @var AddressInterface|null $address */
        $address = $order->getShippingAddress();

        /** @var Przelewy24 $przelewy24 */
        $przelewy24 = $this->paymentApiClientProvider->provideFromPaymentRequest(
            paymentRequest: $paymentRequest,
        );

        $transaction = $przelewy24->transactions()->register(
            sessionId: $payload->sessionId(),
            amount: $order->getTotal(),
            description: $this->paymentDescriptionProvider->getPaymentDescription($payment),
            email: $customer?->getEmail(),
            urlReturn: $payload->afterUrl(),
            language: $this->parseLanguage($order->getLocaleCode()),
            currency: $this->parseCurrency($order->getCurrencyCode()),
            country: $this->parseCountry($address?->getCountryCode()),
            client: $address?->getFullName(),
            address: $address?->getStreet(),
            zip: $address?->getPostcode(),
            city: $address?->getCity(),
            phone: $address?->getPhoneNumber(),
            urlStatus: $payload->notifyUrl(),
            channel: $channel?->value,
            waitForResult: true,
            regulationAccept: false,
            shipping: $order->getShippingTotal(),
            methodRefId: $methodRefId,
            cart: $this->prepareCartItems($order),
        );

        $this->paymentPayloadTransactionTokenAssigner->assign(
            paymentRequest: $paymentRequest,
            transactionToken: $transaction->token(),
        );

        $this->paymentResponseGatewayUrlAssigner->assign(
            paymentRequest: $paymentRequest,
            gatewayUrl: $transaction->gatewayUrl(),
        );
    }

    private function parseLanguage(?string $localeCode): Language
    {
        /** @var string|null $languageCode */
        $languageCode = \Locale::parseLocale($localeCode)['language'] ?? null;

        if (null === $languageCode) {
            return Language::ENGLISH;
        }

        return Language::tryFrom($languageCode) ?? Language::ENGLISH;
    }

    private function parseCurrency(?string $currencyCode): Currency
    {
        return Currency::from($currencyCode ?? '');
    }

    private function parseCountry(?string $countryCode): Country
    {
        return Country::from($countryCode ?? '');
    }

    /**
     * @return CartItem[]
     */
    private function prepareCartItems(OrderInterface $order): array
    {
        $sellerId = (string) $order->getChannel()?->getId();
        $sellerCategory = $order->getChannel()?->getName();

        $cartItems = $order->getItems()->map(
            fn (OrderItemInterface $item): CartItem => new CartItem(
                sellerId: $sellerId,
                sellerCategory: $sellerCategory,
                name: $item->getProduct()->getName(),
                description: $item->getProduct()->getDescription(),
                quantity: $item->getQuantity(),
                price: $item->getUnitPrice(),
                number: (string) $item->getProduct()->getId(),
            ),
        );

        return $cartItems->toArray();
    }
}
