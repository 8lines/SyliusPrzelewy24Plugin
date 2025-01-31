<?php

declare(strict_types=1);

namespace BitBag\SyliusPrzelewy24Plugin\Shared\Przelewy24\Transaction\Creator;

use BitBag\SyliusPrzelewy24Plugin\Shared\Assigner\TransactionPayloadTokenAssignerInterface;
use BitBag\SyliusPrzelewy24Plugin\Shared\Assigner\TransactionResponseGatewayUrlAssignerInterface;
use BitBag\SyliusPrzelewy24Plugin\Shared\Creator\CreatableTransactionRequest;
use BitBag\SyliusPrzelewy24Plugin\Shared\Provider\PaymentApiClientProviderInterface;
use Przelewy24\Enums\Country;
use Przelewy24\Enums\Currency;
use Przelewy24\Enums\Language;
use Przelewy24\Enums\TransactionChannel;
use Przelewy24\Przelewy24;

trait Przelewy24TransactionCreatorTrait
{
    public function __construct(
        private readonly PaymentApiClientProviderInterface $paymentApiClientProvider,
        private readonly TransactionPayloadTokenAssignerInterface $transactionPayloadTokenAssigner,
        private readonly TransactionResponseGatewayUrlAssignerInterface $transactionResponseGatewayUrlAssigner,
    ) {
    }

    public function registerTransaction(
        CreatableTransactionRequest $request,
        string $description,
        ?TransactionChannel $channel = null,
        ?string $methodRefId = null,
    ): void {
        $payload = $request->getTransactionPayload();
        $payload->validateNotNull([
            'sessionId',
            'notifyUrl',
            'afterUrl',
        ]);

        $customer = $request->getCustomer();
        $address = $request->getBillingAddress();

        /** @var Przelewy24 $przelewy24 */
        $przelewy24 = $this->paymentApiClientProvider->provideFromPaymentMethod(
            paymentMethod: $request->getPaymentMethod(),
        );

        $transaction = $przelewy24->transactions()->register(
            sessionId: $payload->sessionId(),
            amount: $request->getOrderTotal(),
            description: $description,
            email: $customer?->getEmail(),
            urlReturn: $payload->afterUrl(),
            language: $this->parseLanguage($request->getLocaleCode()),
            currency: $this->parseCurrency($request->getCurrencyCode()),
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
            shipping: $request->getShippingTotal(),
            methodRefId: $methodRefId,
        );

        $this->transactionPayloadTokenAssigner->assign(
            request: $request,
            transactionToken: $transaction->token(),
        );

        $this->transactionResponseGatewayUrlAssigner->assign(
            request: $request,
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
        if (null === $currencyCode) {
            return Currency::USD;
        }

        return Currency::from($currencyCode);
    }

    private function parseCountry(?string $countryCode): Country
    {
        if (null === $countryCode) {
            return Country::UNITED_STATES;
        }

        return Country::from($countryCode);
    }
}
