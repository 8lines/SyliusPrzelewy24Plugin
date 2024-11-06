<?php

declare(strict_types=1);

namespace BitBag\SyliusPrzelewy24Plugin\Subscription\Entity;

use Doctrine\ORM\Mapping as ORM;

trait RecurringProductVariantTrait
{
    /**
     * @ORM\OneToOne(targetEntity="BitBag\SyliusPrzelewy24Plugin\Subscription\Entity\Przelewy24ProductVariant", cascade={"all"}, fetch="EAGER")
     * @ORM\JoinColumn(name="przelewy24_product_variant_id", referencedColumnName="id", nullable=false)
     */
    #[ORM\OneToOne(targetEntity: Przelewy24ProductVariant::class,cascade: ['all'], fetch: 'EAGER')]
    #[ORM\JoinColumn(name: 'przelewy24_product_variant_id', referencedColumnName: 'id', nullable: false)]
    private Przelewy24ProductVariantInterface $przelewy24ProductVariant;

    public function getPrzelewy24ProductVariant(): Przelewy24ProductVariantInterface
    {
        return $this->przelewy24ProductVariant;
    }

    public function setPrzelewy24ProductVariant(Przelewy24ProductVariantInterface $przelewy24ProductVariant): void
    {
        $this->przelewy24ProductVariant = $przelewy24ProductVariant;
    }
}
