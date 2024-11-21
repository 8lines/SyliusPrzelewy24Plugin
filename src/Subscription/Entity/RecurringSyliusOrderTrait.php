<?php

declare(strict_types=1);

namespace BitBag\SyliusPrzelewy24Plugin\Subscription\Entity;

use Doctrine\ORM\Mapping as ORM;

trait RecurringSyliusOrderTrait
{
    /**
     * @ORM\OneToOne(inversedBy="syliusOrder", targetEntity="RecurringOrder", cascade={"all"}, fetch="EAGER")
     * @ORM\JoinColumn(name="przelewy24_recurring_order_id", referencedColumnName="id", nullable=false)
     */
    #[ORM\OneToOne(inversedBy: 'syliusOrder', targetEntity: RecurringOrder::class, cascade: ['all'], fetch: 'EAGER')]
    #[ORM\JoinColumn(name: 'przelewy24_recurring_order_id', referencedColumnName: 'id', nullable: false)]
    private RecurringOrderInterface $przelewy24Order;

    public function __construct()
    {
        $this->przelewy24Order = new RecurringOrder();
        $this->przelewy24Order->setSyliusOrder($this);
    }

    public function getRecurringPrzelewy24Order(): RecurringOrderInterface
    {
        return $this->przelewy24Order;
    }

    public function setRecurringPrzelewy24Order(RecurringOrderInterface $przelewy24Order): void
    {
        $this->przelewy24Order = $przelewy24Order;
    }

    public function getRecurringPrzelewy24Product(): ?RecurringProductVariantInterface
    {
        foreach ($this->getItems() as $item) {
            /** @var RecurringSyliusProductVariantInterface $variant */
            $variant = $item->getVariant();

            if (true === $variant->getRecurringPrzelewy24ProductVariant()->isRecurring()) {
                return $variant->getRecurringPrzelewy24ProductVariant();
            }
        }

        return null;
    }
}
