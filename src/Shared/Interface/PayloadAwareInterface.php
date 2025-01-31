<?php

declare(strict_types=1);

namespace BitBag\SyliusPrzelewy24Plugin\Shared\Interface;

use BitBag\SyliusPrzelewy24Plugin\Shared\Payload\TransactionPayloadInterface;

interface PayloadAwareInterface
{
    public function getTransactionPayload(): TransactionPayloadInterface;

    public function setTransactionPayload(TransactionPayloadInterface $transactionPayload): void;
}
