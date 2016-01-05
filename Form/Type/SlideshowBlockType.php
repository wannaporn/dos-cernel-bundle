<?php

namespace DoS\CernelBundle\Form\Type;

use Liip\ImagineBundle\Imagine\Filter\FilterConfiguration;
use Sylius\Bundle\ContentBundle\Form\Type\SlideshowBlockType as BaseSlideshowBlockType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;

class SlideshowBlockType extends BaseSlideshowBlockType
{
    /**
     * ImagineBlockType constructor.
     *
     * @param string $dataClass
     * @param array $validationGroups
     * @param FilterConfiguration $filterConfiguration
     */
    public function __construct(
        $dataClass,
        array $validationGroups,
        FilterConfiguration $filterConfiguration
    ) {
        $this->dataClass = $dataClass;
        $this->validationGroups = $validationGroups;
        $this->filterConfiguration = $filterConfiguration;
    }

    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options = array())
    {
        $builder
            ->add('filter', 'text', array(
                'label' => 'sylius.form.imagine_block.filter',
                'required' => false,
            ))
            ->add('parentDocument', null)
            ->add('name', 'text', array(
                'label' => 'sylius.form.slideshow_block.internal_name'
            ))
            ->add('title', 'text', array(
                'label' => 'sylius.form.slideshow_block.title'
            ))
            ->add('children', 'collection', array(
                'type' => 'sylius_imagine_block',
                'allow_add' => true,
                'allow_delete' => true,
                'by_reference' => false,
                'label' => false,
                'button_add_label' => 'sylius.form.slideshow_block.add_slide',
                'cascade_validation' => true,
            ))
            ->add('publishable', null, array(
                'label' => 'sylius.form.slideshow_block.publishable'
            ))
            ->add('publishStartDate', 'datetime', array(
                'required' => false,
                'html5' => false,
                'empty_value' => '',
                'date_widget' => 'single_text',
                'time_widget' => 'single_text',
                'label' => 'sylius.form.slideshow_block.publish_start_date',
            ))
            ->add('publishEndDate', 'datetime', array(
                'required' => false,
                'html5' => false,
                'empty_value' => '',
                'date_widget' => 'single_text',
                'time_widget' => 'single_text',
                'label' => 'sylius.form.slideshow_block.publish_end_date',
            ))
        ;

        $builder
            ->addEventListener(FormEvents::PRE_SUBMIT, function(FormEvent $event) {
                $data = $event->getData();

                if (empty($data['children'])) {
                    return;
                }

                foreach($data['children'] as $key => &$children) {
                    $children['filter'] = $data['filter'];
                    $children['name'] = is_numeric($key) ? ($data['name'] .'-'. $key) : $key;
                }

                $event->setData($data);
            })
        ;
    }
}
