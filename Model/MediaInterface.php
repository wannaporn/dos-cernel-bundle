<?php

namespace DoS\CernelBundle\Model;

use Sylius\Component\Media\Model\ImageInterface;

interface MediaInterface extends ImageInterface
{
	/**
     * @return null|string
     */
    public function getPath();

    /**
     * @param null|string $path
     */
    public function setPath($path);
}
