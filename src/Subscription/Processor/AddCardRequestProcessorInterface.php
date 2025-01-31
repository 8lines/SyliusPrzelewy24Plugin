<?php

declare(strict_types=1);

namespace BitBag\SyliusPrzelewy24Plugin\Subscription\Processor;

use BitBag\SyliusPrzelewy24Plugin\Subscription\Entity\AddCardRequestInterface;

interface AddCardRequestProcessorInterface
{
    /**
     * @param callable<AddCardRequestInterface, void> $action
     */
    public function process(
        AddCardRequestInterface $request,
        callable $action,
    ): void;
}
