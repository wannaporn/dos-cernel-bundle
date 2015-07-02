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

use DateTimeZone;
use Symfony\Component\Translation\TranslatorInterface;

class SettingsRequirements extends RequirementCollection
{
    const REQUIRED_PHP_VERSION = '5.5.2';

    public function __construct(TranslatorInterface $translator)
    {
        parent::__construct($translator->trans('dos.settings.header', array(), 'requirements'));

        $on = $translator->trans('dos.settings.on', array(), 'requirements');
        $off = $translator->trans('dos.settings.off', array(), 'requirements');

        $this
            ->add(new Requirement(
                $translator->trans('dos.settings.version', array(), 'requirements'),
                version_compare(phpversion(), self::REQUIRED_PHP_VERSION, '>='),
                '>='.self::REQUIRED_PHP_VERSION,
                phpversion()
            ))
            ->add(new Requirement(
                $translator->trans('dos.settings.version_recommanded', array(), 'requirements'),
                version_compare(phpversion(), '5.3.8', '>='),
                '>=5.3.8',
                phpversion(),
                false
            ))
            ->add(new Requirement(
                $translator->trans('dos.settings.timezone', array(), 'requirements'),
                $this->isOn('date.timezone'),
                $translator->trans('dos.settings.any', array(), 'requirements'),
                ini_get('date.timezone')
            ))
        ;

        if (version_compare(phpversion(), self::REQUIRED_PHP_VERSION, '>=')) {
            $this->add(new Requirement(
                $translator->trans('dos.settings.timezone_deprecated.header', array(), 'requirements'),
                in_array(date_default_timezone_get(), DateTimeZone::listIdentifiers()),
                $translator->trans('dos.settings.non_deprecated', array(), 'requirements'),
                date_default_timezone_get(),
                true,
                $translator->trans('dos.settings.timezone_deprecated.help', array('%timezone%' => date_default_timezone_get()), 'requirements')
            ));
        }

        $this
            ->add(new Requirement(
                $translator->trans('dos.settings.detect_unicode', array(), 'requirements'),
                !$this->isOn('detect_unicode'),
                $on,
                ini_get('detect_unicode'),
                false
            ))
            ->add(new Requirement(
                $translator->trans('dos.settings.detect_unicode', array(), 'requirements'),
                !$this->isOn('detect_unicode'),
                $on,
                ini_get('detect_unicode'),
                false
            ))
            ->add(new Requirement(
                $translator->trans('dos.settings.short_open_tag', array(), 'requirements'),
                !$this->isOn('short_open_tag'),
                $off,
                ini_get('short_open_tag'),
                false
            ))
            ->add(new Requirement(
                $translator->trans('dos.settings.magic_quotes_gpc', array(), 'requirements'),
                !$this->isOn('magic_quotes_gpc'),
                $off,
                ini_get('magic_quotes_gpc'),
                false
            ))
            ->add(new Requirement(
                $translator->trans('dos.settings.register_globals', array(), 'requirements'),
                !$this->isOn('register_globals'),
                $off,
                ini_get('register_globals'),
                false
            ))
            ->add(new Requirement(
                $translator->trans('dos.settings.session.auto_start', array(), 'requirements'),
                !$this->isOn('session.auto_start'),
                $off,
                ini_get('session.auto_start'),
                false
            ))
        ;
    }

    private function isOn($key)
    {
        $value = ini_get($key);

        return false !== $value && 'off' !== $value;
    }
}
