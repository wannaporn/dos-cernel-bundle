<?php

/*
 * This file is part of the Sylius package.
 *
 * (c) Paweł Jędrzejewski
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace DoS\CernelBundle\Requirement;

use Symfony\Component\Translation\TranslatorInterface;

class FilesystemRequirements extends RequirementCollection
{
    public function __construct(TranslatorInterface $translator, $root, $cacheDir, $logDir)
    {
        parent::__construct($translator->trans('dos.filesystem.header', array(), 'requirements'));

        $exists = $translator->trans('dos.filesystem.exists', array(), 'requirements');
        $notExists = $translator->trans('dos.filesystem.not_exists', array(), 'requirements');
        $writable = $translator->trans('dos.filesystem.writable', array(), 'requirements');
        $notWritable = $translator->trans('dos.filesystem.not_writable', array(), 'requirements');

        $this
            ->add(new Requirement(
                $translator->trans('dos.filesystem.vendors', array(), 'requirements'),
                $status = is_dir($root.'/../vendor'),
                $exists,
                $status ? $exists : $notExists
            ))
            ->add(new Requirement(
                $translator->trans('dos.filesystem.cache.header', array(), 'requirements'),
                $status = is_writable($cacheDir),
                $translator->trans('dos.filesystem.writable', array(), 'requirements'),
                $status ? $translator->trans('dos.filesystem.writable', array(), 'requirements') : $translator->trans('dos.filesystem.not_writable', array(), 'requirements'),
                true,
                $translator->trans('dos.filesystem.cache.help', array('%path%' => $cacheDir), 'requirements')
            ))
            ->add(new Requirement(
                $translator->trans('dos.filesystem.logs.header', array(), 'requirements'),
                $status = is_writable($logDir),
                $writable,
                $status ? $writable : $notWritable,
                true,
                $translator->trans('dos.filesystem.logs.help', array('%path%' => $logDir), 'requirements')
            ))
            ->add(new Requirement(
                $translator->trans('dos.filesystem.parameters.header', array(), 'requirements'),
                $status = is_writable($root.'/config/parameters.yml'),
                $writable,
                $status ? $writable : $notWritable,
                true,
                $translator->trans('dos.filesystem.parameters.help', array('%path%' => $root.'/config/parameters.yml'), 'requirements')
            ))
        ;
    }
}
