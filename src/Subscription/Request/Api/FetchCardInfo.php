<?php

declare(strict_types=1);

namespace BitBag\SyliusPrzelewy24Plugin\Subscription\Request\Api;

use Payum\Core\Request\Generic;
use Przelewy24\Api\Responses\Card\CardInfoResponse;

class FetchCardInfo extends Generic
{
    private CardInfoResponse $cardInfo;

    public function getCardInfo(): CardInfoResponse
    {
        return $this->cardInfo;
    }

    public function setCardInfo(CardInfoResponse $cardInfo): void
    {
        $this->cardInfo = $cardInfo;
    }
}
