<?php

declare(strict_types=1);

namespace BitBag\SyliusPrzelewy24Plugin\Shared\Verifier;

interface TransactionVerifierInterface
{
    public function verify(VerifiableRequestInterface $request): void;
}
