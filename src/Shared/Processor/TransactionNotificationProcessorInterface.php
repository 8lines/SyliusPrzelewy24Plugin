<?php

declare(strict_types=1);

namespace BitBag\SyliusPrzelewy24Plugin\Shared\Processor;

interface TransactionNotificationProcessorInterface
{
    public function process(NotificationRequestInterface $request): void;
}
