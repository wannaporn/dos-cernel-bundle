<?php

namespace DoS\CernelBundle\Controller;

use Symfony\Cmf\Bundle\MediaBundle\Controller\ImageController as BaseImageController;
use Symfony\Component\HttpFoundation\RedirectResponse;

class ImageController extends BaseImageController
{
    public function displayAction($path)
    {
        if (preg_match('/http/', $path)) {
            return new RedirectResponse($path);
        }

        return parent::displayAction($path);
    }
}
