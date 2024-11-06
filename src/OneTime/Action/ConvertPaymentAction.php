<?php

/*
 * This file was created by developers working at BitBag
 * Do you need more information about us and what we do? Visit our https://bitbag.io website!
 * We are hiring developers from all over the world. Join us and start your new, exciting adventure and become part of us: https://bitbag.io/career
*/

declare(strict_types=1);

namespace BitBag\SyliusPrzelewy24Plugin\OneTime\Action;

use BitBag\SyliusPrzelewy24Plugin\Shared\Action\BaseConvertPaymentAction;
use Payum\Core\Exception\RequestNotSupportedException;
use Payum\Core\Request\Convert;

final class ConvertPaymentAction extends BaseConvertPaymentAction
{
    public function execute($request): void
    {
        /** @var Convert $request */
        RequestNotSupportedException::assertSupports($this, $request);

        $request->setResult(
            result: $this->extractRequiredDataFromSyliusPayment($request),
        );
    }
}
