<?php

namespace BitBag\SyliusPrzelewy24Plugin\Shared\Request;

use Payum\Core\Request\Generic;
use Przelewy24\Enums\TransactionChannel;

class RegisterTransaction extends Generic
{
    private ?TransactionChannel $channel = null;

    private ?string $methodRefId = null;

    private ?string $gatewayUrl = null;

    private ?string $transactionToken = null;

    public function getChannel(): ?TransactionChannel
    {
        return $this->channel;
    }

    public function setChannel(TransactionChannel $channel): void
    {
        $this->channel = $channel;
    }

    public function getMethodRefId(): ?string
    {
        return $this->methodRefId;
    }

    public function setMethodRefId(string $methodRefId): void
    {
        $this->methodRefId = $methodRefId;
    }

    public function getGatewayUrl(): ?string
    {
        return $this->gatewayUrl;
    }

    public function setGatewayUrl(string $gatewayUrl): void
    {
        $this->gatewayUrl = $gatewayUrl;
    }

    public function getTransactionToken(): ?string
    {
        return $this->transactionToken;
    }

    public function setTransactionToken(string $transactionToken): void
    {
        $this->transactionToken = $transactionToken;
    }
}
