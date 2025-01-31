<?php

declare(strict_types=1);

namespace BitBag\SyliusPrzelewy24Plugin\Shared\Assigner;

use Sylius\Resource\Generator\RandomnessGeneratorInterface;

final readonly class TransactionPayloadSessionIdAssigner implements TransactionPayloadDataAssignerInterface
{
    public const SESSION_ID_LENGTH = 32;

    public function __construct(
        private RandomnessGeneratorInterface $randomnessGenerator,
    ) {
    }

    public function assign(PayloadAssignableRequestInterface $request): void
    {
        $sessionId = $this->randomnessGenerator->generateUriSafeString(
            length: self::SESSION_ID_LENGTH,
        );

        $payload = $request->getTransactionPayload();
        $payload->withSessionId($sessionId);

        $request->setTransactionPayload($payload);
    }
}
