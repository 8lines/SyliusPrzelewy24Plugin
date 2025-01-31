<?php

declare(strict_types=1);

namespace BitBag\SyliusPrzelewy24Plugin\Subscription\Entity;

use BitBag\SyliusPrzelewy24Plugin\Shared\Charger\CardChargeableRequestInterface;
use BitBag\SyliusPrzelewy24Plugin\Shared\Checker\ValidableNotificationRequestInterface;
use BitBag\SyliusPrzelewy24Plugin\Shared\Creator\CreatableTransactionRequest;
use BitBag\SyliusPrzelewy24Plugin\Shared\Processor\NotificationRequestInterface;
use BitBag\SyliusPrzelewy24Plugin\Shared\Refunder\RefundableRequestInterface;
use BitBag\SyliusPrzelewy24Plugin\Shared\Synchronizer\SynchronizableRequestInterface;
use BitBag\SyliusPrzelewy24Plugin\Shared\Verifier\VerifiableRequestInterface;
use Sylius\Component\Core\Model\CustomerInterface;
use Sylius\Component\Core\Model\PaymentMethodInterface;
use Sylius\Resource\Model\ResourceInterface;
use Symfony\Component\Uid\Uuid;

interface AddCardRequestInterface extends
    ResourceInterface,
    SynchronizableRequestInterface,
    ValidableNotificationRequestInterface,
    VerifiableRequestInterface,
    CardChargeableRequestInterface,
    CreatableTransactionRequest,
    RefundableRequestInterface,
    NotificationRequestInterface
{
    public const REQUEST_LOCALE_CODE = 'pl_PL';

    public const REQUEST_CURRENCY_CODE = 'PLN';

    public const REQUEST_ORDER_TOTAL = 1000;

    public const ACTION_CAPTURE = 'capture';

    public const ACTION_NOTIFY = 'notify';

    public const STATE_NEW = 'new';

    public const STATE_COMPLETED = 'completed';

    public const STATE_CANCELLED = 'cancelled';

    public const STATE_FAILED = 'failed';

    public const STATE_PROCESSING = 'processing';

    public function getHash(): ?Uuid;

    public function getAction(): string;

    public function setAction(string $action): void;

    public function getState(): string;

    public function setState(string $state): void;

    public function getCustomer(): CustomerInterface;

    public function setCustomer(CustomerInterface $customer): void;

    public function getPaymentMethod(): PaymentMethodInterface;

    public function setPaymentMethod(PaymentMethodInterface $paymentMethod): void;

    public function getParameters(): mixed;

    public function setParameters(#[\SensitiveParameter] mixed $parameters): void;

    public function getPayload(): mixed;

    public function setPayload(#[\SensitiveParameter] mixed $payload): void;

    public function getResponse(): mixed;

    public function setResponse(#[\SensitiveParameter] mixed $response): void;
}
