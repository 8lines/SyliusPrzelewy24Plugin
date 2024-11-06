<?php

declare(strict_types=1);

namespace BitBag\SyliusPrzelewy24Plugin\Shared\Action\Api;

use BitBag\SyliusPrzelewy24Plugin\Shared\Request\RegisterTransaction;
use Payum\Core\Action\ActionInterface;
use Payum\Core\ApiAwareInterface;
use Payum\Core\ApiAwareTrait;
use Payum\Core\Bridge\Spl\ArrayObject;
use Payum\Core\Exception\RequestNotSupportedException;
use Przelewy24\Api\Requests\Items\CartItem;
use Przelewy24\Enums\Country;
use Przelewy24\Enums\Currency;
use Przelewy24\Enums\Language;
use Przelewy24\Przelewy24;
use Webmozart\Assert\Assert;

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

        $originAmount = $model['originAmount'] ?? null;
        $shipping = $model['shipping'] ?? null;

        Assert::notNull(
            value: $originAmount,
            message: 'Origin amount is required to register a transaction.'
        );

        Assert::notNull(
            value: $shipping,
            message: 'Shipping is required to register a transaction.'
        );

        /** @var Przelewy24 $api */
        $api = $this->api;

        $language = Language::tryFrom($model['language']) ?? Language::ENGLISH;
        $currency = Currency::from($model['currency']);
        $channel = $request->getChannel()?->value;
        $country = Country::from($model['country']);
        $methodRefId = $request->getMethodRefId();
        $cart = $this->prepareCartItems($model['cart']);

        $registeredTransaction = $api->transactions()->register(
            sessionId: $model['sessionId'],
            amount: $originAmount,
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
            channel: $channel,
            waitForResult: true,
            regulationAccept: false,
            shipping: $shipping,
            methodRefId: $methodRefId,
            cart: $cart,
        );

        $request->setGatewayUrl($registeredTransaction->gatewayUrl());
        $request->setTransactionToken($registeredTransaction->token());
    }

    public function supports($request): bool
    {
        return $request instanceof RegisterTransaction
            && $request->getModel() instanceof \ArrayAccess;
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
            'sessionId',
            'language',
            'currency',
            'country',
            'cart',
            'description',
            'email',
            'urlReturn',
            'client',
            'address',
            'zip',
            'city',
            'urlStatus',
        ];
    }
}
