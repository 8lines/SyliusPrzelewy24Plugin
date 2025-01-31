<?php

declare(strict_types=1);

namespace BitBag\SyliusPrzelewy24Plugin\Shared\Payload;

use Przelewy24\Enums\Currency;

interface TransactionPayloadInterface
{
    public static function empty(): self;

    public function sessionId(): ?string;

    public function withSessionId(string $sessionId): void;

    public function orderId(): ?int;

    public function withOrderId(int $orderId): void;

    public function transactionToken(): ?string;

    public function withTransactionToken(string $transactionToken): void;

    public function notifyUrl(): ?string;

    public function withNotifyUrl(string $notifyUrl): void;

    public function afterUrl(): ?string;

    public function withAfterUrl(string $afterUrl): void;

    public function amount(): ?int;

    public function withAmount(int $amount): void;

    public function currency(): ?Currency;

    public function withCurrency(Currency $currency): void;

    public function statement(): ?string;

    public function withStatement(string $statement): void;

    public function methodId(): ?int;

    public function withMethodId(int $methodId): void;

    public function cardRefId(): ?string;

    public function withCardRefId(string $cardRefId): void;

    public function validateNotNull(array $fields): void;
}
