<?php

declare(strict_types=1);

namespace BitBag\SyliusPrzelewy24Plugin\Subscription\Transitions;

final readonly class SubscriptionIntervalTransitions
{
    public const GRAPH = 'bitbag_sylius_przelewy24_plugin_subscription_interval';

    public const TRANSITION_SCHEDULE = 'schedule';

    public const TRANSITION_AWAIT_PAYMENT = 'await_payment';

    public const TRANSITION_ACTIVATE = 'activate';

    public const TRANSITION_COMPLETE = 'complete';

    public const TRANSITION_ABORT = 'abort';

    private function __construct()
    {
    }
}
