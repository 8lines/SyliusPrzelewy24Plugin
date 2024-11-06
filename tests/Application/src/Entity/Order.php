<?php

declare(strict_types=1);

namespace Tests\BitBag\SyliusPrzelewy24Plugin\Application\src\Entity;

use BitBag\SyliusPrzelewy24Plugin\Subscription\Entity\Przelewy24Order;
use BitBag\SyliusPrzelewy24Plugin\Subscription\Entity\RecurringOrderInterface;
use BitBag\SyliusPrzelewy24Plugin\Subscription\Entity\RecurringOrderTrait;
use Sylius\Component\Core\Model\Order as BaseOrder;

class Order extends BaseOrder implements RecurringOrderInterface
{
    use RecurringOrderTrait;

    public function __construct()
    {
        parent::__construct();

        $this->przelewy24Order = new Przelewy24Order();
    }
}
