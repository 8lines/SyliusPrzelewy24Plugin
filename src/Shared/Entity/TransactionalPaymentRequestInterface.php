<?php

declare(strict_types=1);

namespace BitBag\SyliusPrzelewy24Plugin\Shared\Entity;

use BitBag\SyliusPrzelewy24Plugin\Shared\Charger\CardChargeableRequestInterface;
use BitBag\SyliusPrzelewy24Plugin\Shared\Checker\ValidableNotificationRequestInterface;
use BitBag\SyliusPrzelewy24Plugin\Shared\Creator\CreatableTransactionRequest;
use BitBag\SyliusPrzelewy24Plugin\Shared\Processor\NotificationRequestInterface;
use BitBag\SyliusPrzelewy24Plugin\Shared\Refunder\RefundableRequestInterface;
use BitBag\SyliusPrzelewy24Plugin\Shared\Synchronizer\SynchronizableRequestInterface;
use BitBag\SyliusPrzelewy24Plugin\Shared\Verifier\VerifiableRequestInterface;
use Sylius\Component\Core\Model\OrderInterface;
use Sylius\Component\Payment\Model\PaymentRequestInterface as BasePaymentRequestInterface;

interface TransactionalPaymentRequestInterface extends
    BasePaymentRequestInterface,
    SynchronizableRequestInterface,
    ValidableNotificationRequestInterface,
    VerifiableRequestInterface,
    CardChargeableRequestInterface,
    CreatableTransactionRequest,
    RefundableRequestInterface,
    NotificationRequestInterface
{
    public function getOrder(): ?OrderInterface;
}
