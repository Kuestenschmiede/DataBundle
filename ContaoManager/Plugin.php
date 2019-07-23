<?php

namespace con4gis\MapContentBundle\ContaoManager;

use con4gis\CoreBundle\con4gisCoreBundle;
use con4gis\CloudBundle\con4gisCloudBundle;
use con4gis\MapContentBundle\con4gisMapContentBundle;
use con4gis\MapsBundle\con4gisMapsBundle;
use con4gis\ProjectsBundle\con4gisProjectsBundle;
use Contao\CoreBundle\ContaoCoreBundle;
use Contao\ManagerPlugin\Bundle\Config\BundleConfig;
use Contao\ManagerPlugin\Bundle\Config\ConfigInterface;
use Contao\ManagerPlugin\Bundle\Parser\ParserInterface;
use Contao\ManagerPlugin\Bundle\BundlePluginInterface;
use Contao\ManagerPlugin\Routing\RoutingPluginInterface;
use Symfony\Component\Config\Loader\LoaderResolverInterface;
use Symfony\Component\HttpKernel\KernelInterface;
use Contao\ManagerPlugin\Config\ConfigPluginInterface;
use Symfony\Component\Config\Loader\LoaderInterface;

class Plugin implements BundlePluginInterface, ConfigPluginInterface
{
    /**
     * Gets a list of autoload configurations for this bundle.
     *
     * @param ParserInterface $parser
     *
     * @return ConfigInterface[]
     */
    public function getBundles(ParserInterface $parser)
    {
        return [
            BundleConfig::create(con4gisMapContentBundle::class)
                ->setLoadAfter([con4gisProjectsBundle::class, con4gisMapsBundle::class]),
        ];
    }
    
    public function registerContainerConfiguration(LoaderInterface $loader, array $managerConfig)
    {
        $loader->load('@con4gisMapContentBundle/Resources/config/config.yml');
    }
    
}