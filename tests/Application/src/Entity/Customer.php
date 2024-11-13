<?php

declare(strict_types=1);

namespace Tests\BitBag\SyliusPrzelewy24Plugin\Application\src\Entity;

use BitBag\SyliusPrzelewy24Plugin\Subscription\Entity\CustomerInterface;
use BitBag\SyliusPrzelewy24Plugin\Subscription\Entity\CustomerTrait;
use Sylius\Component\Core\Model\Customer as BaseCustomer;

class Customer extends BaseCustomer implements CustomerInterface
{
    use CustomerTrait {
        __construct as private __customerConstruct;
    }

    public function __construct()
    {
        parent::__construct();

        $this->__customerConstruct();
    }
}
