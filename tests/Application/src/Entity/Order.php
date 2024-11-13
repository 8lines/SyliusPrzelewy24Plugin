<?php

declare(strict_types=1);

namespace Tests\BitBag\SyliusPrzelewy24Plugin\Application\src\Entity;

use BitBag\SyliusPrzelewy24Plugin\Subscription\Entity\RecurringOrderInterface;
use BitBag\SyliusPrzelewy24Plugin\Subscription\Entity\RecurringOrderTrait;
use Sylius\Component\Core\Model\Order as BaseOrder;

class Order extends BaseOrder implements RecurringOrderInterface
{
    use RecurringOrderTrait {
        __construct as private __orderConstruct;
    }

    public function __construct()
    {
        parent::__construct();

        $this->__orderConstruct();
    }
}
