<?php

declare(strict_types=1);

namespace BitBag\SyliusPrzelewy24Plugin\Subscription\Entity;

use Doctrine\ORM\Mapping as ORM;

trait RecurringOrderTrait
{
    /**
     * @ORM\OneToOne(targetEntity="BitBag\SyliusPrzelewy24Plugin\Subscription\Entity\Przelewy24Order", cascade={"all"}, fetch="EAGER")
     * @ORM\JoinColumn(name="przelewy24_order_id", referencedColumnName="id", nullable=false)
     */
    #[ORM\OneToOne(targetEntity: Przelewy24Order::class, cascade: ['all'], fetch: 'EAGER')]
    #[ORM\JoinColumn(name: 'przelewy24_order_id', referencedColumnName: 'id', nullable: false)]
    private Przelewy24OrderInterface $przelewy24Order;

    public function getPrzelewy24Order(): Przelewy24OrderInterface
    {
        return $this->przelewy24Order;
    }

    public function setPrzelewy24Order(Przelewy24OrderInterface $przelewy24Order): void
    {
        $this->przelewy24Order = $przelewy24Order;
    }

    public function getPrzelewy24RecurringProduct(): ?Przelewy24ProductVariantInterface
    {
        foreach ($this->getItems() as $item) {
            /** @var RecurringProductVariantInterface $variant */
            $variant = $item->getVariant();

            if (true === $variant->getPrzelewy24ProductVariant()->isRecurring()) {
                return $variant->getPrzelewy24ProductVariant();
            }
        }

        return null;
    }
}
