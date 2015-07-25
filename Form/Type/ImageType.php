<?php

namespace DoS\CernelBundle\Form\Type;

use Sylius\Bundle\MediaBundle\Form\Type\ImageType as BaseImageType;

class ImageType extends BaseImageType
{
    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return 'dos_image';
    }
}
