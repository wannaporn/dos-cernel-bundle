<?php

namespace DoS\CernelBundle\Config;

use DoS\ResourceBundle\DependencyInjection\AbstractResourceExtension;
use Symfony\Component\Config\Definition\ConfigurationInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;

abstract class AbstractExtension extends AbstractResourceExtension
{
    protected $applicationName = 'toro';

    /**
     * {@inheritdoc}
     */
    public function load(array $configs, ContainerBuilder $container)
    {
        $this->configure($configs, $this->getBundleConfiguration(), $container,
            self::CONFIGURE_LOADER |
            self::CONFIGURE_DATABASE |
            self::CONFIGURE_PARAMETERS |
            self::CONFIGURE_VALIDATORS |
            self::CONFIGURE_FORMS
        );
    }

    /**
     * @return ConfigurationInterface
     */
    abstract protected function getBundleConfiguration();
}
