<?php

declare(strict_types=1);

namespace BitBag\SyliusPrzelewy24Plugin\Subscription\Payload;

use BitBag\SyliusPrzelewy24Plugin\Shared\Payload\TransactionPayloadInterface;
use Przelewy24\Enums\Currency;
use Webmozart\Assert\Assert;

final class AddCardPayload implements TransactionPayloadInterface
{
    private function __construct(
        private ?string $sessionId = null,
        private ?int $orderId = null,
        private ?string $transactionToken = null,
        private ?string $notifyRequestHash = null,
        private ?string $notifyUrl = null,
        private ?string $afterUrl = null,
        private ?int $amount = null,
        private ?Currency $currency = null,
        private ?string $statement = null,
        private ?int $methodId = null,
        private ?string $cardRefId = null,
    ) {
    }

    public static function create(
        ?string $sessionId,
        ?int $orderId,
        ?string $transactionToken,
        ?string $notifyRequestHash,
        ?string $notifyUrl,
        ?string $afterUrl,
        ?int $amount,
        ?Currency $currency,
        ?string $statement,
        ?int $methodId,
        ?string $cardRefId,
    ): self {
        return new self(
            sessionId: $sessionId,
            orderId: $orderId,
            transactionToken: $transactionToken,
            notifyRequestHash: $notifyRequestHash,
            notifyUrl: $notifyUrl,
            afterUrl: $afterUrl,
            amount: $amount,
            currency: $currency,
            statement: $statement,
            methodId: $methodId,
            cardRefId: $cardRefId,
        );
    }

    public static function empty(): self
    {
        return new self();
    }

    public function sessionId(): ?string
    {
        return $this->sessionId;
    }

    public function withSessionId(string $sessionId): void
    {
        $this->sessionId = $sessionId;
    }

    public function orderId(): ?int
    {
        return $this->orderId;
    }

    public function withOrderId(int $orderId): void
    {
        $this->orderId = $orderId;
    }

    public function transactionToken(): ?string
    {
        return $this->transactionToken;
    }

    public function withTransactionToken(string $transactionToken): void
    {
        $this->transactionToken = $transactionToken;
    }

    public function notifyRequestHash(): ?string
    {
        return $this->notifyRequestHash;
    }

    public function withNotifyRequestHash(string $notifyRequestHash): void
    {
        $this->notifyRequestHash = $notifyRequestHash;
    }

    public function notifyUrl(): ?string
    {
        return $this->notifyUrl;
    }

    public function withNotifyUrl(string $notifyUrl): void
    {
        $this->notifyUrl = $notifyUrl;
    }

    public function afterUrl(): ?string
    {
        return $this->afterUrl;
    }

    public function withAfterUrl(string $afterUrl): void
    {
        $this->afterUrl = $afterUrl;
    }

    public function amount(): ?int
    {
        return $this->amount;
    }

    public function withAmount(int $amount): void
    {
        $this->amount = $amount;
    }

    public function currency(): ?Currency
    {
        return $this->currency;
    }

    public function withCurrency(Currency $currency): void
    {
        $this->currency = $currency;
    }

    public function statement(): ?string
    {
        return $this->statement;
    }

    public function withStatement(string $statement): void
    {
        $this->statement = $statement;
    }

    public function methodId(): ?int
    {
        return $this->methodId;
    }

    public function withMethodId(int $methodId): void
    {
        $this->methodId = $methodId;
    }

    public function cardRefId(): ?string
    {
        return $this->cardRefId;
    }

    public function withCardRefId(string $cardRefId): void
    {
        $this->cardRefId = $cardRefId;
    }

    public function validateNotNull(array $fields): void
    {
        foreach ($fields as $field) {
            Assert::notNull(
                value: $this->$field,
                message: \sprintf('Field "%s" cannot be null.', $field),
            );
        }
    }

    public function toArray(): array
    {
        return [
            'sessionId' => $this->sessionId,
            'orderId' => $this->orderId,
            'transactionToken' => $this->transactionToken,
            'notifyRequestHash' => $this->notifyRequestHash,
            'notifyUrl' => $this->notifyUrl,
            'afterUrl' => $this->afterUrl,
            'amount' => $this->amount,
            'currency' => $this->currency?->value,
            'statement' => $this->statement,
            'methodId' => $this->methodId,
            'cardRefId' => $this->cardRefId,
        ];
    }

    public static function fromArray(array $data): self
    {
        $requiredKeys = [
            'sessionId',
            'orderId',
            'transactionToken',
            'notifyRequestHash',
            'notifyUrl',
            'afterUrl',
            'amount',
            'currency',
            'statement',
            'methodId',
            'cardRefId',
        ];

        foreach ($requiredKeys as $key) {
            Assert::keyExists(
                array: $data,
                key: $key,
                message: \sprintf('Field "%s" is required.', $key),
            );
        }

        $payload = new self(
            sessionId: $data['sessionId'],
            orderId: $data['orderId'],
            transactionToken: $data['transactionToken'],
            notifyRequestHash: $data['notifyRequestHash'],
            notifyUrl: $data['notifyUrl'],
            afterUrl: $data['afterUrl'],
            amount: $data['amount'],
            statement: $data['statement'],
            methodId: $data['methodId'],
            cardRefId: $data['cardRefId'],
        );

        if (null !== $data['currency']) {
            $payload->withCurrency(Currency::from($data['currency']));
        }

        return $payload;
    }
}
