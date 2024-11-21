<?php

declare(strict_types=1);

namespace BitBag\SyliusPrzelewy24Plugin\Subscription\Entity;

use Doctrine\ORM\Mapping as ORM;

trait RecurringSyliusProductVariantTrait
{
    /**
     * @ORM\OneToOne(targetEntity="RecurringProductVariant", cascade={"all"}, fetch="EAGER")
     * @ORM\JoinColumn(name="przelewy24_recurring_product_variant_id", referencedColumnName="id", nullable=false)
     */
    #[ORM\OneToOne(targetEntity: RecurringProductVariant::class,cascade: ['all'], fetch: 'EAGER')]
    #[ORM\JoinColumn(name: 'przelewy24_recurring_product_variant_id', referencedColumnName: 'id', nullable: false)]
    private RecurringProductVariantInterface $przelewy24ProductVariant;

    public function __construct()
    {
        $this->przelewy24ProductVariant = new RecurringProductVariant();
    }

    public function getRecurringPrzelewy24ProductVariant(): RecurringProductVariantInterface
    {
        return $this->przelewy24ProductVariant;
    }

    public function setRecurringPrzelewy24ProductVariant(RecurringProductVariantInterface $przelewy24ProductVariant): void
    {
        $this->przelewy24ProductVariant = $przelewy24ProductVariant;
    }
}
