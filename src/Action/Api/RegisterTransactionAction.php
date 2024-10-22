<?php

declare(strict_types=1);

namespace BitBag\SyliusPrzelewy24Plugin\Action\Api;

use BitBag\SyliusPrzelewy24Plugin\Request\Api\RegisterTransaction;
use Payum\Core\Action\ActionInterface;
use Payum\Core\ApiAwareInterface;
use Payum\Core\ApiAwareTrait;
use Payum\Core\Bridge\Spl\ArrayObject;
use Payum\Core\Exception\RequestNotSupportedException;
use Payum\Core\Reply\HttpRedirect;
use Przelewy24\Api\Requests\Items\CartItem;
use Przelewy24\Api\Responses\Transaction\RegisterTransactionResponse;
use Przelewy24\Enums\Country;
use Przelewy24\Enums\Currency;
use Przelewy24\Enums\Language;
use Przelewy24\Przelewy24;

final class RegisterTransactionAction implements ActionInterface, ApiAwareInterface
{
    use ApiAwareTrait;

    public function __construct()
    {
        $this->apiClass = Przelewy24::class;
    }

    public function execute($request): void
    {
        /** @var RegisterTransaction $request */
        RequestNotSupportedException::assertSupports($this, $request);

        $model = ArrayObject::ensureArrayObject($request->getModel());
        $model->validateNotEmpty($this->requiredModelFields());

        $model['sessionId'] = uniqid();

        $registeredTransaction = $this->registerTransaction($model);

        throw new HttpRedirect($registeredTransaction->gatewayUrl());
    }

    public function supports($request): bool
    {
        return $request instanceof RegisterTransaction
            && $request->getModel() instanceof \ArrayAccess;
    }

    private function registerTransaction($model): RegisterTransactionResponse
    {
        /** @var Przelewy24 $api */
        $api = $this->api;

        $language = Language::tryFrom($model['language']) ?? Language::ENGLISH;
        $currency = Currency::from($model['currency']);
        $country = Country::from($model['country']);
        $cart = $this->prepareCartItems($model['cart']);

        return $api->transactions()->register(
            sessionId: $model['sessionId'],
            amount: $model['originAmount'],
            description: $model['description'],
            email: $model['email'],
            urlReturn: $model['urlReturn'],
            language: $language,
            currency: $currency,
            country: $country,
            client: $model['client'],
            address: $model['address'],
            zip: $model['zip'],
            city: $model['city'],
            phone: $model['phone'] ?? null,
            urlStatus: $model['urlStatus'],
            waitForResult: true,
            regulationAccept: false,
            shipping: $model['shipping'],
            cart: $cart,
        );
    }

    private function prepareCartItems(array $cart): array
    {
        return \array_map(
            callback: fn (array $item): CartItem => new CartItem(
                sellerId: $item['sellerId'],
                sellerCategory: $item['sellerCategory'],
                name: $item['name'],
                description: $item['description'],
                quantity: $item['quantity'],
                price: $item['price'],
                number: $item['number'],
            ),
            array: $cart,
        );
    }

    private function requiredModelFields(): array
    {
        return [
            'language',
            'currency',
            'country',
            'cart',
            'originAmount',
            'description',
            'email',
            'urlReturn',
            'client',
            'address',
            'zip',
            'city',
            'urlStatus',
            'shipping',
        ];
    }
}
