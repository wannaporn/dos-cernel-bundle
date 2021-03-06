<?php

namespace DoS\CernelBundle\Command;

use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class InstallAssetsCommand extends AbstractInstallCommand
{
    /**
     * {@inheritdoc}
     */
    protected function configure()
    {
        $this
            ->setName('dos:install:assets')
            ->setDescription('Installs all Project assets.')
            ->setHelp(<<<EOT
The <info>%command.name%</info> command downloads and installs all Project media assets.
EOT
            )
        ;
    }

    /**
     * {@inheritdoc}
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $output->writeln(sprintf('Installing Project assets for environment <info>%s</info>.', $this->getEnvironment()));

        $commands = array(
            'assets:install' => array('--symlink' => true),
            'assetic:dump',
        );

        $this->runCommands($commands, $input, $output);
    }
}
