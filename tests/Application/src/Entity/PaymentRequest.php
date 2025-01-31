<?php

declare(strict_types=1);

namespace Tests\BitBag\SyliusPrzelewy24Plugin\App\Entity;

use BitBag\SyliusPrzelewy24Plugin\Shared\Entity\TransactionalPaymentRequestInterface;
use BitBag\SyliusPrzelewy24Plugin\Shared\Entity\TransactionalPaymentRequestTrait;
use Sylius\Component\Payment\Model\PaymentRequest as BasePaymentRequest;

class PaymentRequest extends BasePaymentRequest implements TransactionalPaymentRequestInterface
{
    use TransactionalPaymentRequestTrait;
}
