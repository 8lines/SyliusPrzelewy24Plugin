<?php

declare(strict_types=1);

namespace BitBag\SyliusPrzelewy24Plugin\Subscription\Cloner;

use Sylius\Component\Core\Model\AdjustmentInterface;

interface AdjustmentClonerInterface
{
    public function clone(AdjustmentInterface $baseAdjustment): AdjustmentInterface;
}
