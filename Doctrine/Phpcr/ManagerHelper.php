<?php

namespace DoS\CernelBundle\Doctrine\Phpcr;

use Doctrine\ODM\PHPCR\DocumentManager;
use Symfony\Cmf\Bundle\MediaBundle\Doctrine\Phpcr\Directory;

/**
 * @author liverbool <phaiboon@intbizth.com>
 */
class ManagerHelper
{
    /**
     * Clean up paths.
     *
     * @return string
     */
    public static function cleanPath()
    {
        $paths = array();
        foreach (func_get_args() as $path) {
            $paths[] = $path;
        }

        return preg_replace('|//|', '/', join('/', $paths));
    }

    /**
     * @param $path
     *
     * @return array
     */
    public static function explodePath($path)
    {
        return explode('/', self::cleanPath($path));
    }

    /**
     * @param DocumentManager $dm
     * @param $path
     *
     * @return Directory[]
     */
    public static function mkdirs(DocumentManager $dm, $path)
    {
        $paths = self::explodePath($path);
        $parent = null;
        $dirs = array();

        foreach ($paths as $path) {
            if ($dir = $dm->find(null, self::cleanPath($parent, $path))) {
                $dirs[] = $dir;
            } else {
                $dirs[] = self::mkdir($dm, $parent, $path);
            }

            $parent = self::cleanPath($parent, $path);
        }

        return $dirs;
    }

    /**
     * @param DocumentManager $dm
     * @param $path
     * @param $name
     *
     * @return bool|Directory
     */
    public static function mkdir(DocumentManager $dm, $path, $name)
    {
        $dirname = self::cleanPath($path, $name);

        if ($dm->find(null, $dirname)) {
            return false;
        }

        $dir = new Directory();
        $dir->setName($name);
        $dir->setId($dirname);

        $dm->persist($dir);
        $dm->flush();

        return $dir;
    }
}
