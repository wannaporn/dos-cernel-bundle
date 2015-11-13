<?php

namespace DoS\CernelBundle\Form\Type;

use Sylius\Bundle\ContentBundle\Form\Type\ImagineBlockType as BaseImagineBlockType;
use Symfony\Component\Form\FormBuilderInterface;

class ImagineBlockType extends BaseImagineBlockType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options = array())
    {
        parent::buildForm($builder, $options);

        $builder
            ->remove('name')
            ->add('name', 'hidden')

            ->remove('filter')
            ->add('filter', 'hidden')

            ->remove('parentDocument')

            ->remove('publishable')
            ->remove('publishStartDate')
            ->remove('publishEndDate')
        ;
    }
}
