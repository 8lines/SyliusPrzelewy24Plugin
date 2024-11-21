<?php

declare(strict_types=1);

namespace BitBag\SyliusPrzelewy24Plugin\Subscription\Entity;

use Doctrine\ORM\Mapping as ORM;

trait SyliusCustomerAsSubscriberTrait
{
    /**
     * @ORM\OneToOne(inversedBy="syliusCustomer", targetEntity="Subscriber", cascade={"all"}, fetch="EAGER")
     * @ORM\JoinColumn(name="przelewy24_customer_id", referencedColumnName="id", nullable=false)
     */
    #[ORM\OneToOne(inversedBy: 'syliusCustomer', targetEntity: Subscriber::class, cascade: ['all'], fetch: 'EAGER')]
    #[ORM\JoinColumn(name: 'przelewy24_subscriber_id', referencedColumnName: 'id', nullable: false)]
    private SubscriberInterface $przelewy24Subscriber;

    public function __construct()
    {
        $this->przelewy24Subscriber = new Subscriber();
        $this->przelewy24Subscriber->setSyliusCustomer($this);
    }

    public function getPrzelewy24Subscriber(): SubscriberInterface
    {
        return $this->przelewy24Subscriber;
    }

    public function setPrzelewy24Subscriber(SubscriberInterface $przelewy24Subscriber): void
    {
        $this->przelewy24Subscriber = $przelewy24Subscriber;
    }
}
