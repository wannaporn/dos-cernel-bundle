<?php

namespace DoS\CernelBundle;

use DoS\CernelBundle\Config\AbstractBundle;
use DoS\CernelBundle\DependencyInjection\DoSCernelExtension;
use DoS\CernelBundle\DependencyInjection\Compiler;
use Symfony\Component\DependencyInjection\ContainerBuilder;

class DoSCernelBundle extends AbstractBundle
{
    /**
     * {@inheritdoc}
     */
    public function getContainerExtension()
    {
        return new DoSCernelExtension();
    }

    /**
     * {@inheritdoc}
     */
    public function build(ContainerBuilder $builder)
    {
        $builder->addCompilerPass(new Compiler\LiipImagineControllerPass());
    }

    /**
     * {@inheritdoc}
     */
    public function getModelInterfaces()
    {
        return array(
            'DoS\CernelBundle\Model\MediaInterface' => 'sylius.model.image.class',
        );
    }
}
