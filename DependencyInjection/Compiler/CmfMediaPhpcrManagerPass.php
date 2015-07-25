<?php

namespace DoS\CernelBundle\DependencyInjection\Compiler;

use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;

class CmfMediaPhpcrManagerPass implements CompilerPassInterface
{
    /**
     * {@inheritdoc}
     */
    public function process(ContainerBuilder $container)
    {
        if (!$container->getParameter('dos.cmf.enabled')) {
            return;
        }

        if (!$container->getParameter('dos.cmf.custom_nameing')) {
            return;
        }

        $container
            ->getDefinition('cmf_media.persistence.phpcr.manager')
            ->setClass($container->getParameter('dos.cmf.persistence.phpcr.manager.class'))
        ;
    }

}
