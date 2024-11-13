<?php

declare(strict_types=1);

namespace BitBag\SyliusPrzelewy24Plugin\Subscription\Menu;

use Sylius\Bundle\AdminBundle\Event\ProductVariantMenuBuilderEvent;

final class ProductVariantMenuListener
{
    public function addRecurringOptionsToProductVariantMenu(ProductVariantMenuBuilderEvent $event): void
    {
        $menu = $event->getMenu();
        $menu
            ->addChild('recurring')
            ->setAttribute('template', '@BitBagSyliusPrzelewy24Plugin/Admin/ProductVariant/Tab/_recurring.html.twig')
            ->setLabel('bitbag_sylius_przelewy24_plugin.ui.recurring')
        ;
    }
}
