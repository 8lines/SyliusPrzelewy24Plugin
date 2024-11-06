<?php

declare(strict_types=1);

namespace BitBag\SyliusPrzelewy24Plugin\Subscription\Transition;

final class Przelewy24SubscriptionScheduleIntervalTransition
{
    public const GRAPH = 'bitbag_sylius_przelewy24_plugin_przelewy24_subscription_schedule_interval_graph';

    public const TRANSITION_SCHEDULE = 'schedule';

    public const TRANSITION_AWAIT_PAYMENT = 'await_payment';

    public const TRANSITION_ACTIVATE = 'activate';

    public const TRANSITION_COMPLETE = 'complete';

    public const TRANSITION_ABORT = 'abort';

    private function __construct()
    {
    }
}
