<?php

namespace DoS\CernelBundle\Twig\Extension;

use Sylius\Bundle\MediaBundle\Twig\Extension\SyliusImageExtension;

class Media extends SyliusImageExtension
{
    /**
     * @var string
     * @see liip_imagine.filters
     */
    protected $filterPrefix = 'size_';

    /**
     * @var string
     * @see Resources/routing/liip_imagine.xml
     */
    protected $routePrefix = '/m/c/r/';

    /**
     * @param string $filterPrefix
     * @param string $routePrefix
     */
    public function setPrefixs($filterPrefix, $routePrefix)
    {
        $this->filterPrefix = $filterPrefix;
        $this->routePrefix = $routePrefix;
    }

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

        return str_replace(
            $this->routePrefix . $this->filterPrefix,
            $this->routePrefix,
            parent::getImageUrl($image, $options, $default)
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
