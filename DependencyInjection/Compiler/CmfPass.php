<?php

namespace DoS\CernelBundle\DependencyInjection\Compiler;

use DoS\CernelBundle\Controller\ImageController;
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
        if (!$container->hasParameter('dos.cmf.enabled')) {
            return;
        }

        $container->setParameter('cmf_media.image_controller.class', ImageController::CLASS);

        if (!$container->hasDefinition('sylius.form.type.slideshow_block')) {
            $imagineBlock = $container->getDefinition('sylius.form.type.slideshow_block');
            $imagineBlock->addArgument(new Reference('liip_imagine.filter.configuration'));
        }
    }
}
