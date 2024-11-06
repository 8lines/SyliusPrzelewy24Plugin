<?php

/*
 * This file was created by developers working at BitBag
 * Do you need more information about us and what we do? Visit our https://bitbag.io website!
 * We are hiring developers from all over the world. Join us and start your new, exciting adventure and become part of us: https://bitbag.io/career
*/

declare(strict_types=1);

namespace BitBag\SyliusPrzelewy24Plugin\Subscription\Action;

use BitBag\SyliusPrzelewy24Plugin\Shared\Action\BaseConvertPaymentAction;
use BitBag\SyliusPrzelewy24Plugin\Subscription\Entity\RecurringOrderInterface;
use Payum\Core\Exception\RequestNotSupportedException;
use Payum\Core\Request\Convert;
use Sylius\Component\Core\Model\PaymentInterface;

final class ConvertPaymentAction extends BaseConvertPaymentAction
{
    public function execute($request): void
    {
        /** @var Convert $request */
        RequestNotSupportedException::assertSupports($this, $request);

        $syliusPaymentData = $this->extractRequiredDataFromSyliusPayment($request);
        $subscriptionData = $this->getSubscriptionData($request);

        $request->setResult(
            result: \array_merge($syliusPaymentData, $subscriptionData),
        );
    }

    private function getSubscriptionData(Convert $request): array
    {
        /** @var PaymentInterface $recurringPayment */
        $recurringPayment = $request->getSource();

        /** @var RecurringOrderInterface $recurringOrder */
        $recurringOrder = $recurringPayment->getOrder();

        $subscriptionData = [];

        $subscriptionData['recurring'] = $recurringOrder->getPrzelewy24Order()->isRecurring() ?? false;
        $subscriptionData['sequence'] = $recurringOrder->getPrzelewy24Order()->getRecurringSequenceIndex();
        $subscriptionData['cardRefId'] = $recurringPayment->getDetails()['cardRefId'] ?? null;

        $subscriptionData['initialTransaction'] = $subscriptionData['sequence'] === 0;
        $subscriptionData['payWithExistingCard'] = $subscriptionData['cardRefId'] !== null;

        return $subscriptionData;
    }
}
