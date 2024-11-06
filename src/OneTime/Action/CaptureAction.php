<?php

/*
 * This file was created by developers working at BitBag
 * Do you need more information about us and what we do? Visit our https://bitbag.io website!
 * We are hiring developers from all over the world. Join us and start your new, exciting adventure and become part of us: https://bitbag.io/career
*/

declare(strict_types=1);

namespace BitBag\SyliusPrzelewy24Plugin\OneTime\Action;

use BitBag\SyliusPrzelewy24Plugin\OneTime\Request\Api\RegisterOneTimeTransaction;
use BitBag\SyliusPrzelewy24Plugin\Shared\Action\BaseCaptureAction;
use Payum\Core\Bridge\Spl\ArrayObject;
use Payum\Core\Exception\RequestNotSupportedException;
use Payum\Core\GatewayAwareTrait;
use Payum\Core\Request\Capture;
use Payum\Core\Security\GenericTokenFactoryAwareTrait;

final class CaptureAction extends BaseCaptureAction
{
    use GatewayAwareTrait;
    use GenericTokenFactoryAwareTrait;

    public function execute($request): void
    {
        /** @var Capture $request */
        RequestNotSupportedException::assertSupports($this, $request);

        $this->initializeTokensAndAssignSessionId($request);
        $this->registerOneTimeTransaction($request);
    }

    private function registerOneTimeTransaction(Capture $request): void
    {
        $model = ArrayObject::ensureArrayObject($request->getModel());
        $this->gateway->execute(new RegisterOneTimeTransaction($model));
    }
}
