<?php

namespace DoS\CernelBundle\Doctrine\Phpcr;

use Doctrine\Common\Util\ClassUtils;
use Doctrine\ODM\PHPCR\DocumentManager;
use PHPCR\Util\PathHelper;
use Symfony\Cmf\Bundle\MediaBundle\Doctrine\Phpcr\MediaManager as BaseMediaManager;
use Symfony\Cmf\Bundle\MediaBundle\MediaInterface;

class MediaManager extends BaseMediaManager
{
    /**
     * {@inheritdoc}
     */
    public function setDefaults(MediaInterface $media, $parentPath = null)
    {
        $class = ClassUtils::getClass($media);
        // check and add name if possible
        if (!$media->getName()) {
            if ($media->getId()) {
                $media->setName(PathHelper::getNodeName($media->getId()));
            } else {
                throw new \RuntimeException(sprintf(
                    'Unable to set defaults, Media of type "%s" does not have a name or id.',
                    $class
                ));
            }
        }
        $rootPath = is_null($parentPath) ? $this->rootPath : $parentPath;
        $path = ($rootPath === '/' ? $rootPath : $rootPath . '/') . $media->getName();
        /** @var DocumentManager $dm */
        $dm = $this->getObjectManager();
        // TODO use PHPCR autoname
        if ($dm->find($class, $path)) {
            // path already exists
            $ext = pathinfo($media->getName(), PATHINFO_EXTENSION);
            $media->setName(md5($media->getName()) . '_' . time() . '_' . rand() . ($ext ? '.' . $ext : ''));
        }
        if (!$media->getParent()) {
            $parent = $dm->find(null, PathHelper::getParentPath($path));
            $media->setParent($parent);
        }
    }
}
