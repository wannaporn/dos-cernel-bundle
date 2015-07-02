<?php

namespace DoS\CernelBundle;

use DoS\CernelBundle\Config\AbstractBundle;
use DoS\CernelBundle\DependencyInjection\DoSCernelExtension;

class DoSCernelBundle extends AbstractBundle
{
    /**
     * {@inheritdoc}
     */
    public function getContainerExtension()
    {
        return new DoSCernelExtension();
    }
}
