<?php

namespace DoS\CernelBundle\DependencyInjection\Compiler;

use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;

class LiipImagineControllerPass implements CompilerPassInterface
{
    /**
     * {@inheritdoc}
     */
    public function process(ContainerBuilder $container)
    {
        if (!$container->hasDefinition('liip_imagine.controller')) {
            return;
        }

        $container
            ->getDefinition('liip_imagine.controller')
            ->setClass($container->getParameter('dos.liip_imagine.controller.class'))
        ;

        $container->setParameter('liip_imagine.controller.class', 'dos.liip_imagine.controller.class');
    }

}
