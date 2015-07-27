<?php

namespace DoS\CernelBundle\Twig\Extension;

use Sylius\Bundle\MediaBundle\Twig\Extension\SyliusImageExtension;

class Media extends SyliusImageExtension
{
    /**
     * {@inheritdoc}
     */
    public function getFunctions() {
        return array(
            new \Twig_SimpleFunction('twig_image_url',
                array($this, 'getImageUrl'),
                array('is_safe' => array('html'))
            ),
        );
    }

    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return 'twig_cmf_media';
    }
}
