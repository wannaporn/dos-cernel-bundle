<?php

namespace DoS\CernelBundle\Config;

use DoS\ResourceBundle\DependencyInjection\AbstractResourceExtension;
use Symfony\Component\Config\Definition\ConfigurationInterface;

abstract class AbstractExtension extends AbstractResourceExtension
{
    protected $applicationName = 'toro';
}
