<?php

declare(strict_types=1);

namespace BitBag\SyliusPrzelewy24Plugin\Subscription\Transitions;

final readonly class AddCardRequestTransitions
{
    public const GRAPH = 'bitbag_sylius_przelewy24_plugin_add_card_request';

    public const TRANSITION_COMPLETE = 'complete';

    public const TRANSITION_CANCEL = 'cancel';

    public const TRANSITION_FAIL = 'fail';

    public const TRANSITION_PROCESS = 'process';

    private function __construct()
    {
    }
}
