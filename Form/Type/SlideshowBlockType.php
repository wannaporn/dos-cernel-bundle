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
        $filters = array();

        foreach (array_keys($this->filterConfiguration->all()) as $filter) {
            $filters[$filter] = $filter;
        }

        parent::buildForm($builder, $options);

        $builder
            ->add('filter', 'choice', array(
                'choices' => $filters,
                'label' => 'sylius.form.imagine_block.filter',
                'required' => false,
            ))

            ->remove('publishStartDate')
            ->add('publishStartDate', 'datetime', array(
                'required' => false,
                'html5' => false,
                'empty_value' => '',
                'date_widget' => 'single_text',
                'time_widget' => 'single_text',
                'label' => 'sylius.form.slideshow_block.publish_start_date',
            ))

            ->remove('publishEndDate')
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

                $i = 0;
                foreach($data['children'] as &$children) {
                    $children['filter'] = $data['filter'];
                    $children['name'] = $data['name'] .'-'. $i;
                    $i++;
                }

                $event->setData($data);
            })
        ;
    }
}
