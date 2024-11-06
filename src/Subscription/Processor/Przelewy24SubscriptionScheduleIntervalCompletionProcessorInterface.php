<?php

declare(strict_types=1);

namespace BitBag\SyliusPrzelewy24Plugin\Subscription\Processor;

interface Przelewy24SubscriptionScheduleIntervalCompletionProcessorInterface
{
    public function process(): void;
}
