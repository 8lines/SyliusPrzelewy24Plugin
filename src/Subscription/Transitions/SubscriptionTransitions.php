<?php

declare(strict_types=1);

namespace BitBag\SyliusPrzelewy24Plugin\Subscription\Transitions;

final readonly class SubscriptionTransitions
{
    public const GRAPH = 'bitbag_sylius_przelewy24_plugin_subscription';

    public const TRANSITION_COMPLETE = 'complete';

    public const TRANSITION_ABORT = 'abort';

    private function __construct()
    {
    }
}
