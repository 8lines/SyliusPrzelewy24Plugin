<?php

declare(strict_types=1);

namespace BitBag\SyliusPrzelewy24Plugin\Subscription\Action;

use BitBag\SyliusPrzelewy24Plugin\Shared\Action\BaseCaptureAction;
use BitBag\SyliusPrzelewy24Plugin\Subscription\Request\Api\ChargeCard;
use BitBag\SyliusPrzelewy24Plugin\Subscription\Request\Api\RegisterSubscriptionTransaction;
use Payum\Core\Bridge\Spl\ArrayObject;
use Payum\Core\Exception\InvalidArgumentException;
use Payum\Core\Exception\RequestNotSupportedException;
use Payum\Core\Request\Capture;
use Przelewy24\Enums\TransactionStatus;

final class CaptureAction extends BaseCaptureAction
{
    public function execute($request): void
    {
        /** @var Capture $request */
        RequestNotSupportedException::assertSupports($this, $request);
        $model = ArrayObject::ensureArrayObject($request->getModel());

        $this->initializeTokensAndAssignSessionId($request);

        $recurring = $model['recurring'] ?? false;
        $initialTransaction = $model['initialTransaction'] ?? false;
        $payWithExistingCard = $model['payWithExistingCard'] ?? false;

        if (false === $recurring) {
            throw new InvalidArgumentException('Processing non-recurring payments is not supported by this gateway');
        }

        $this->gateway->execute(new RegisterSubscriptionTransaction($model));

        if (false === $initialTransaction && true === $payWithExistingCard) {
            $this->gateway->execute(new ChargeCard($model));
            return;
        }

        if (true === $initialTransaction && true === $payWithExistingCard) {
            return;
        }

        throw new InvalidArgumentException('The card token could not be fetched');
    }
}
