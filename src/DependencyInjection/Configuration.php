<?php

declare(strict_types=1);

namespace BitBag\SyliusPrzelewy24Plugin\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

final class Configuration implements ConfigurationInterface
{
    public function getConfigTreeBuilder(): TreeBuilder
    {
        return new TreeBuilder('bitbag_sylius_przelewy24_plugin');
    }
}
