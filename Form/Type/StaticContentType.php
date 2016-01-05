<?php

namespace DoS\CernelBundle\Form\Type;

use Sylius\Bundle\ContentBundle\Form\Type\StaticContentType as BaseStaticContentType;
use Symfony\Component\Form\FormBuilderInterface;

class StaticContentType extends BaseStaticContentType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options = array())
    {
        parent::buildForm($builder, $options);

        $builder
            ->remove('publishStartDate')
            ->add('publishStartDate', 'datetime', array(
                'required' => false,
                'html5' => false,
                'empty_value' => '',
                'date_widget' => 'single_text',
                'time_widget' => 'single_text',
                'label' => 'sylius.form.static_content.publish_start_date',
            ))

            ->remove('publishEndDate')
            ->add('publishEndDate', 'datetime', array(
                'required' => false,
                'html5' => false,
                'empty_value' => '',
                'date_widget' => 'single_text',
                'time_widget' => 'single_text',
                'label' => 'sylius.form.static_content.publish_end_date',
            ))
        ;
    }
}
