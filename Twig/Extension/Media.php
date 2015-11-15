<?php

namespace DoS\CernelBundle\Twig\Extension;

use Liip\ImagineBundle\Templating\Helper\ImagineHelper;
use Sylius\Component\Media\Model\ImageInterface;
use Symfony\Cmf\Bundle\MediaBundle\MediaManagerInterface;
use Symfony\Cmf\Bundle\MediaBundle\ImageInterface as MediaInterface;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

class Media extends \Twig_Extension
{
    /**
     * @var string
     * @see liip_imagine.filters
     */
    protected $filterName = 'sizing';

    /**
     * @var UrlGeneratorInterface
     */
    protected $generator;

    /**
     * @var MediaManagerInterface
     */
    protected $mediaManager;

    /**
     * @var ImagineHelper
     */
    protected $imagineHelper;

    /**
     * Constructor.
     *
     * @param MediaManagerInterface $mediaManager
     * @param UrlGeneratorInterface $router        A Router instance
     * @param ImagineHelper         $imagineHelper Imagine helper to use if available
     */
    public function __construct(MediaManagerInterface $mediaManager, UrlGeneratorInterface $router, ImagineHelper $imagineHelper = null)
    {
        $this->mediaManager  = $mediaManager;
        $this->generator     = $router;
        $this->imagineHelper = $imagineHelper;
    }

    /**
     * @param string $filterName
     */
    public function setPrefixs($filterName)
    {
        $this->filterName = $filterName;
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
    public function getImageUrl($image = null, $options = array(), $default = null)
    {
        if (is_string($options)) {
            $wh = null;

            // numeric only
            if (preg_match('/^([0-9]+)$/', $options)) {
                $wh = $options .'x'. $options;
                // numeric x numeric
            } elseif (preg_match('/^([0-9]+)x([0-9]+)$/', $options)) {
                $wh = $options;
            } else {
                // nothing
            }

            $options = array(
                'imagine_filter' => $this->filterName,
                'runtime_config' => array(
                    'thumbnail' => array(
                        "size" => explode('x', $wh),
                        "mode" => 'inset',
                    )
                ),
            );

            if (null !== $wh && null === $default) {
                $default = 'https://placehold.it/' . $wh .'.jpg';
            }
        }

        if (null === $image) {
            return $default;
        }

        if ($image instanceof ImageInterface) {
            $media = $image->getMedia();
        } else {
            $media = $image;
        }

        return $media ? $this->displayUrl($media, $options) : $default;
    }

    /**
     * Generates a display URL from the given image.
     *
     * @param MediaInterface $file
     * @param array          $options
     * @param Boolean|string $referenceType The type of reference (one of the constants in UrlGeneratorInterface)
     *
     * @return string The generated URL
     */
    protected function displayUrl(MediaInterface $file, array $options = array(), $referenceType = UrlGeneratorInterface::ABSOLUTE_PATH)
    {
        $urlSafePath = $this->mediaManager->getUrlSafePath($file);

        if ($this->imagineHelper && isset($options['imagine_filter']) && is_string($options['imagine_filter'])) {
            return $this->imagineHelper->filter(
                $urlSafePath,
                $options['imagine_filter'],
                !empty($options['runtime_config']) ? $options['runtime_config'] : array()
            );
        }

        return $this->generator->generate('cmf_media_image_display', array('path' => $urlSafePath), $referenceType);
    }

    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return 'twig_cmf_media';
    }
}
