<?php

declare(strict_types=1);

namespace Tests\BitBag\SyliusPrzelewy24Plugin\App\Entity;

use BitBag\SyliusPrzelewy24Plugin\Subscription\Entity\RecurringSyliusProductVariantInterface;
use BitBag\SyliusPrzelewy24Plugin\Subscription\Entity\RecurringSyliusProductVariantTrait;
use Sylius\Component\Core\Model\ProductVariant as BaseProductVariant;

class ProductVariant extends BaseProductVariant implements RecurringSyliusProductVariantInterface
{
    use RecurringSyliusProductVariantTrait {
        __construct as private __productVariantConstruct;
    }

    public function __construct()
    {
        parent::__construct();

        $this->__productVariantConstruct();
    }
}
