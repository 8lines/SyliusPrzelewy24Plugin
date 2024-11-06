<?php

declare(strict_types=1);

namespace BitBag\SyliusPrzelewy24Plugin\Subscription\Cloner;

use Doctrine\ORM\EntityManagerInterface;
use Sylius\Component\Core\Model\AdjustmentInterface;
use Sylius\Resource\Factory\FactoryInterface;

final class DefaultAdjustmentCloner implements AdjustmentClonerInterface
{
    public function __construct(
        private readonly FactoryInterface $adjustmentFactory,
        private readonly EntityManagerInterface $entityManager,
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

        $this->entityManager->persist($clonedAdjustment);

        return $clonedAdjustment;
    }
}
