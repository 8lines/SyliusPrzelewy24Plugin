<?php

declare(strict_types=1);

namespace BitBag\SyliusPrzelewy24Plugin\Subscription\Transition;

final class Przelewy24SubscriptionTransition
{
    public const GRAPH = 'bitbag_sylius_przelewy24_plugin_przelewy24_subscription_graph';

    public const TRANSITION_COMPLETE = 'complete';

    public const TRANSITION_ABORT = 'abort';

    private function __construct()
    {
    }
}
