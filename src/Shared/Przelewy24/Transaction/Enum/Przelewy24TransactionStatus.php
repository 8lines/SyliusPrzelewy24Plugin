<?php

declare(strict_types=1);

namespace BitBag\SyliusPrzelewy24Plugin\Shared\Przelewy24\Transaction\Enum;

use Przelewy24\Enums\TransactionStatus;

enum Przelewy24TransactionStatus: string
{
    case NO_PAYMENT = 'no_payment';

    case PAYMENT_COMPLETED = 'payment_completed';

    case PAYMENT_RETURNED = 'payment_returned';

    case PAYMENT_FAILED = 'payment_failed';

    public static function fromSdkTransactionStatus(TransactionStatus $status): self
    {
        return match ($status) {
            TransactionStatus::NO_PAYMENT => self::NO_PAYMENT,
            TransactionStatus::ADVANCE_PAYMENT => self::PAYMENT_COMPLETED,
            TransactionStatus::PAYMENT_MADE => self::PAYMENT_COMPLETED,
            TransactionStatus::PAYMENT_RETURNED => self::PAYMENT_RETURNED,
        };
    }
}
