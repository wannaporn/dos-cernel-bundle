<?php

namespace DoS\CernelBundle\Model;

use Sylius\Component\Media\Model\Image as BaseImage;

class Media extends BaseImage implements MediaInterface
{
	/**
     * @var string
     */
	protected $path = null;

    /**
     * {@inheritdoc}
     */
    public function getPath()
    {
        return $this->path;
    }

    /**
     * {@inheritdoc}
     */
    public function setPath($path)
    {
        $this->path = $path;
    }
}
