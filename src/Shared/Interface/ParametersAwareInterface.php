<?php

declare(strict_types=1);

namespace BitBag\SyliusPrzelewy24Plugin\Shared\Interface;

interface ParametersAwareInterface
{
    public function getTransactionParameters(): mixed;
}
