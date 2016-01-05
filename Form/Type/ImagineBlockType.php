<?php

namespace DoS\CernelBundle\Form\Type;

use Sylius\Bundle\ContentBundle\Form\Type\ImagineBlockType as BaseImagineBlockType;
use Symfony\Component\Form\FormBuilderInterface;

class ImagineBlockType extends BaseImagineBlockType
{
    public function __construct($dataClass, array $validationGroups) {
        $this->dataClass = $dataClass;
        $this->validationGroups = $validationGroups;
    }

    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options = array())
    {
        $builder
            ->add('parentDocument', null, array(
                'label' => false,
                'required' => false,
                'attr' => array(
                    'class' => 'hidden'
                ),
            ))
            ->add('name', 'hidden')
            ->add('label', 'text', array(
                'label' => 'sylius.form.imagine_block.label',
                'required' => false
            ))
            ->add('linkUrl', 'text', array(
                'label' => 'sylius.form.imagine_block.link_url',
                'required' => false
            ))
            ->add('filter', 'hidden')
            ->add('image', 'cmf_media_image', array(
                'label' => 'sylius.form.imagine_block.image',
                'attr' => array('class' => 'imagine-thumbnail'),
                'required' => false
            ))
        ;
    }
}
