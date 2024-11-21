<?php

declare(strict_types=1);

namespace Tests\BitBag\SyliusPrzelewy24Plugin\App\Entity;

use BitBag\SyliusPrzelewy24Plugin\Subscription\Entity\RecurringSyliusOrderInterface;
use BitBag\SyliusPrzelewy24Plugin\Subscription\Entity\RecurringSyliusOrderTrait;
use Sylius\Component\Core\Model\Order as BaseOrder;

class Order extends BaseOrder implements RecurringSyliusOrderInterface
{
    use RecurringSyliusOrderTrait {
        __construct as private __orderConstruct;
    }

    public function __construct()
    {
        parent::__construct();

        $this->__orderConstruct();
    }
}
