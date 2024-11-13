<?php

declare(strict_types=1);

namespace Tests\BitBag\SyliusPrzelewy24Plugin\Application\src\Entity;

use BitBag\SyliusPrzelewy24Plugin\Subscription\Entity\RecurringProductVariantInterface;
use BitBag\SyliusPrzelewy24Plugin\Subscription\Entity\RecurringProductVariantTrait;
use Sylius\Component\Core\Model\ProductVariant as BaseProductVariant;

class ProductVariant extends BaseProductVariant implements RecurringProductVariantInterface
{
    use RecurringProductVariantTrait {
        __construct as private __productVariantConstruct;
    }

    public function __construct()
    {
        parent::__construct();

        $this->__productVariantConstruct();
    }
}
