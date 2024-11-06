<?php

declare(strict_types=1);

namespace Tests\BitBag\SyliusPrzelewy24Plugin\Application\src\Entity;

use BitBag\SyliusPrzelewy24Plugin\Subscription\Entity\CustomerInterface;
use BitBag\SyliusPrzelewy24Plugin\Subscription\Entity\CustomerTrait;
use BitBag\SyliusPrzelewy24Plugin\Subscription\Entity\Przelewy24Customer;
use Sylius\Component\Core\Model\Customer as BaseCustomer;

class Customer extends BaseCustomer implements CustomerInterface
{
    use CustomerTrait;

    public function __construct()
    {
        parent::__construct();

        $this->przelewy24Customer = new Przelewy24Customer();
    }
}
