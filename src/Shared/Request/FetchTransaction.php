<?php

declare(strict_types=1);

namespace BitBag\SyliusPrzelewy24Plugin\Shared\Request;

use Payum\Core\Request\Generic;
use Przelewy24\Api\Responses\Transaction\FindTransactionResponse;

class FetchTransaction extends Generic
{
    private FindTransactionResponse $transaction;

    public function getTransaction(): FindTransactionResponse
    {
        return $this->transaction;
    }

    public function setTransaction(FindTransactionResponse $transaction): void
    {
        $this->transaction = $transaction;
    }
}
