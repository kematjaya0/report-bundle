<?php

namespace Kematjaya\ReportBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;
/**
 * @author Nur Hidayatullah <kematjaya0@gmail.com>
 */
class Configuration implements ConfigurationInterface 
{
    public function getConfigTreeBuilder(): TreeBuilder
    {
        $treeBuilder = new TreeBuilder('kmj_report');
        $treeBuilder->getRootNode()
                ->children()
                    ->arrayNode('import')
                        ->children()
                            ->scalarNode('document_path')
                                ->defaultValue('%kernel.project_dir%')
                            ->end()
                        ->end()
                    ->end()
                ->end();
        
        return $treeBuilder;
    }
}