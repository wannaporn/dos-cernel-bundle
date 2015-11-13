<?php

namespace DoS\CernelBundle\DependencyInjection\Compiler;

use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Reference;

class CmfPass implements CompilerPassInterface
{
    /**
     * {@inheritdoc}
     */
    public function process(ContainerBuilder $container)
    {
        if (!$container->hasDefinition('sylius.form.type.slideshow_block')) {
            return;
        }

        $imagineBlock = $container->getDefinition('sylius.form.type.slideshow_block');
        $imagineBlock->addArgument(new Reference('liip_imagine.filter.configuration'));
    }
}
