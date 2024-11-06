<?php

declare(strict_types=1);

namespace BitBag\SyliusPrzelewy24Plugin\Subscription\Action\Api;

use BitBag\SyliusPrzelewy24Plugin\Shared\Request\RegisterTransaction;
use BitBag\SyliusPrzelewy24Plugin\Subscription\Request\Api\RegisterSubscriptionTransaction;
use Payum\Core\Action\ActionInterface;
use Payum\Core\Bridge\Spl\ArrayObject;
use Payum\Core\Exception\InvalidArgumentException;
use Payum\Core\Exception\RequestNotSupportedException;
use Payum\Core\GatewayAwareInterface;
use Payum\Core\GatewayAwareTrait;
use Payum\Core\Reply\HttpRedirect;
use Przelewy24\Enums\TransactionChannel;

final class RegisterSubscriptionTransactionAction implements ActionInterface, GatewayAwareInterface
{
    use GatewayAwareTrait;

    public function execute($request): void
    {
        /** @var RegisterSubscriptionTransaction $request */
        RequestNotSupportedException::assertSupports($this, $request);
        $model = ArrayObject::ensureArrayObject($request->getModel());

        $initialTransaction = $model['initialTransaction'] ?? false;
        $payWithExistingCard = $model['payWithExistingCard'] ?? false;

        if (false === $initialTransaction) {
            $model->validateNotEmpty(['cardRefId']);
        }

        $registerTransaction = new RegisterTransaction($model);
        $registerTransaction->setChannel(TransactionChannel::CARDS_ONLY);

        if (false === $initialTransaction || true === $payWithExistingCard) {
            $registerTransaction->setMethodRefId($model['cardRefId']);
        }

        $this->gateway->execute($registerTransaction);

        if (null === $registerTransaction->getTransactionToken()) {
            throw new InvalidArgumentException('The transaction token could not be fetched');
        }

        $model['transactionToken'] = $registerTransaction->getTransactionToken();

        if (true === $initialTransaction && false === $payWithExistingCard) {
            if (null !== $registerTransaction->getGatewayUrl()) {
                throw new HttpRedirect($registerTransaction->getGatewayUrl());
            }

            throw new InvalidArgumentException('The gateway URL could not be fetched');
        }
    }

    public function supports($request): bool
    {
        return $request instanceof RegisterSubscriptionTransaction
            && $request->getModel() instanceof \ArrayAccess;
    }
}
