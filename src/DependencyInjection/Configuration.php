<?php

declare(strict_types=1);

namespace Ikuzo\SyliusStockAlertPlugin\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

final class Configuration implements ConfigurationInterface
{
    /**
     * @psalm-suppress UnusedVariable
     */
    public function getConfigTreeBuilder(): TreeBuilder
    {
        $treeBuilder = new TreeBuilder('ikuso_sylius_stock_alert_plugin');
        $rootNode = $treeBuilder->getRootNode();

        return $treeBuilder;
    }
}
