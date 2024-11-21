<?php

declare(strict_types=1);

namespace Tests\BitBag\SyliusPrzelewy24Plugin\App\Entity;

use BitBag\SyliusPrzelewy24Plugin\Subscription\Entity\SyliusCustomerAsSubscriberInterface;
use BitBag\SyliusPrzelewy24Plugin\Subscription\Entity\SyliusCustomerAsSubscriberTrait;
use Sylius\Component\Core\Model\Customer as BaseCustomer;

class Customer extends BaseCustomer implements SyliusCustomerAsSubscriberInterface
{
    use SyliusCustomerAsSubscriberTrait {
        __construct as private __customerConstruct;
    }

    public function __construct()
    {
        parent::__construct();

        $this->__customerConstruct();
    }
}
