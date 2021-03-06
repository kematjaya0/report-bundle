<?php

namespace Kematjaya\ReportBundle\DependencyInjection;

use Kematjaya\ReportBundle\DependencyInjection\Configuration;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Loader\XmlFileLoader;
use Symfony\Component\DependencyInjection\Extension\Extension;

/**
 * @author Nur Hidayatullah <kematjaya0@gmail.com>
 */
class ReportExtension extends Extension
{
    public function load(array $configs, ContainerBuilder $container) 
    {
        $loader = new XmlFileLoader(
                $container,
                new FileLocator(dirname(__DIR__).'/Resources/config')
            );
        $loader->load('services.xml');
        
        $config = $this->processConfiguration(new Configuration(), $configs);
        $container->setParameter($this->getAlias(), $config);
    }
}
