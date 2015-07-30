<?php

namespace DoS\CernelBundle\Twig\Extension;

use Sylius\Bundle\MediaBundle\Twig\Extension\SyliusImageExtension;

class Media extends SyliusImageExtension
{
    protected $filterPrefix = 'size_';

    /**
     * {@inheritdoc}
     */
    public function getFunctions() {
        return array(
            new \Twig_SimpleFunction('ui_image_url',
                array($this, 'getImageUrl'),
                array('is_safe' => array('html'))
            ),
        );
    }

    /**
     * {@inheritdoc}
     */
    public function getFilters() {
        return array(
            new \Twig_SimpleFilter('ui_image_url',
                array($this, 'getImageUrl'),
                array('is_safe' => array('html'))
            ),
        );
    }

    /**
     * {@inheritdoc}
     */
    public function getImageUrl($image, $options = array(), $default = '')
    {
        if (is_string($options)) {
            // numeric only
            if (preg_match('/^([0-9]+)$/', $options)) {
                $options = sprintf('%s%sx%s',$this->filterPrefix, $options, $options);
                // numeric x numeric
            } elseif (preg_match('/^([0-9]+)x([0-9]+)$/', $options)) {
                $options = sprintf('%s%s', $this->filterPrefix, $options);
            } else {
                // nothing
            }

            $options = array(
                'imagine_filter' => $options
            );
        }

        return parent::getImageUrl($image, $options, $default);
    }

    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return 'twig_cmf_media';
    }
}
