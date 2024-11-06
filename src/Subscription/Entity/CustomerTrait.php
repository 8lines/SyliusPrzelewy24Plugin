<?php

declare(strict_types=1);

namespace BitBag\SyliusPrzelewy24Plugin\Subscription\Entity;

use Doctrine\ORM\Mapping as ORM;

trait CustomerTrait
{
    /**
     * @ORM\OneToOne( targetEntity="BitBag\SyliusPrzelewy24Plugin\Subscription\Entity\Przelewy24Customer", cascade={"all"}, fetch="EAGER")
     * @ORM\JoinColumn(name="przelewy24_customer_id", referencedColumnName="id", nullable=false)
     */
    #[ORM\OneToOne(targetEntity: Przelewy24Customer::class, cascade: ['all'], fetch: 'EAGER')]
    private Przelewy24CustomerInterface $przelewy24Customer;

    public function getPrzelewy24Customer(): Przelewy24CustomerInterface
    {
        return $this->przelewy24Customer;
    }

    public function setPrzelewy24Customer(Przelewy24CustomerInterface $przelewy24Customer): void
    {
        $this->przelewy24Customer = $przelewy24Customer;
    }
}
