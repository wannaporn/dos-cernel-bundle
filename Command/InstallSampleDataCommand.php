<?php

namespace DoS\CernelBundle\Command;

use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class InstallSampleDataCommand extends AbstractInstallCommand
{
    private $infos = array(
        1 => '<error>Warning! This will erase your database.</error> Your current environment is <info>%s</info>.',
        2 => '<question>Load sample data? (y/N)</question> ',
    );

    /**
     * {@inheritdoc}
     */
    protected function configure()
    {
        $this
            ->setName('dos:install:sample-data')
            ->setDescription('Install sample data into Project.')
            ->setHelp(<<<EOT
The <info>%command.name%</info> command loads the sample data for Project.
EOT
            )
        ;
    }

    /**
     * {@inheritdoc}
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $output->writeln(sprintf($this->infos[1], $this->getEnvironment()));

        if ($input->getOption('no-interaction')) {
            return 0;
        }

        if (!$this->getHelperSet()->get('dialog')->askConfirmation($output, $this->infos[2], false)) {
            return 0;
        }

        $output->writeln('Loading sample data...');

        $doctrineConfiguration = $this->get('doctrine.orm.entity_manager')->getConnection()->getConfiguration();
        $logger = $doctrineConfiguration->getSQLLogger();
        $doctrineConfiguration->setSQLLogger(null);

        $commands = array(
        );

        if ($this->getParameter('dos.cmf.enabled')) {
            $commands['doctrine:phpcr:fixtures:load'] = array('--no-interaction' => true);
        }

        $commands['doctrine:fixtures:load'] = array('--no-interaction' => true);

        $this->runCommands($commands, $input, $output);

        $doctrineConfiguration->setSQLLogger($logger);
    }
}
