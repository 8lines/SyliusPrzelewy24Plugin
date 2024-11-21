<?php

declare(strict_types=1);

namespace BitBag\SyliusPrzelewy24Plugin\Shared\Assigner;

use Laminas\Stdlib\PriorityQueue;
use Sylius\Component\Payment\Model\PaymentRequestInterface;

final readonly class CompositePaymentPayloadTransactionSessionAssigner implements PaymentPayloadDataAssignerInterface
{
    /**
     * @var PriorityQueue<PaymentPayloadDataAssignerInterface>
     */
    private PriorityQueue $sessionAssigners;

    public function __construct()
    {
        $this->sessionAssigners = new PriorityQueue();
    }

    public function addAssigner(
        PaymentPayloadDataAssignerInterface $paymentPayloadGeneratedItemAssigner,
        int $priority = 0,
    ): void {
        $this->sessionAssigners->insert(
            data: $paymentPayloadGeneratedItemAssigner,
            priority: $priority,
        );
    }

    public function assign(PaymentRequestInterface $paymentRequest): void
    {
        /** @var PaymentPayloadDataAssignerInterface $sessionAssigner */
        foreach ($this->sessionAssigners as $sessionAssigner) {
            $sessionAssigner->assign($paymentRequest);
        }
    }
}
