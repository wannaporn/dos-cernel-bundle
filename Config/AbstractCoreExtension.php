<?php

namespace DoS\CernelBundle\Config;

use Symfony\Component\Config\Definition\ConfigurationInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Extension\PrependExtensionInterface;

abstract class AbstractCoreExtension extends AbstractExtension implements PrependExtensionInterface
{
    /**
     * @var array
     */
    private $bundles = array(
        'sylius_api',
        'sylius_settings',
        'sylius_mailer',
        'sylius_rbac',
        'sylius_user',
    );

    /**
     * {@inheritdoc}
     */
    public function prepend(ContainerBuilder $container)
    {
        $config = $this->processConfiguration(
            $this->getBundleConfiguration(),
            $container->getExtensionConfig($this->getAlias())
        );

        foreach ($container->getExtensions() as $name => $extension) {
            if (in_array($name, $this->bundles)) {
                $container->prependExtensionConfig($name, array('driver' => $config['driver']));
            }
        }
    }
}
