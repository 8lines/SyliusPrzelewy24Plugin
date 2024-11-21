<?php

declare(strict_types=1);

namespace BitBag\SyliusPrzelewy24Plugin\Subscription\Cloner;

use Sylius\Component\Core\Model\AdjustmentInterface;
use Sylius\Resource\Factory\FactoryInterface;

final readonly class AdjustmentCloner implements AdjustmentClonerInterface
{
    public function __construct(
        private FactoryInterface $adjustmentFactory,
    ) {
    }

    public function clone(AdjustmentInterface $baseAdjustment): AdjustmentInterface
    {
        /** @var AdjustmentInterface $clonedAdjustment */
        $clonedAdjustment = $this->adjustmentFactory->createNew();

        $clonedAdjustment->setAmount($baseAdjustment->getAmount());
        $clonedAdjustment->setType($baseAdjustment->getType());
        $clonedAdjustment->setDetails($baseAdjustment->getDetails());
        $clonedAdjustment->setLabel($baseAdjustment->getLabel());
        $clonedAdjustment->setNeutral($baseAdjustment->isNeutral());

        if (true === $baseAdjustment->isLocked()) {
            $clonedAdjustment->lock();
        } else {
            $clonedAdjustment->unlock();
        }

        return $clonedAdjustment;
    }
}
