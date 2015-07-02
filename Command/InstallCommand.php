<?php

namespace DoS\CernelBundle\Command;

use DoS\CernelBundle\Cernel;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class InstallCommand extends AbstractInstallCommand
{
    /**
     * {@inheritdoc}
     */
    protected function configure()
    {
        $this
            ->setName('dos:install')
            ->setDescription('Installs Project in your preferred environment.')
            ->setHelp(<<<EOT
The <info>%command.name%</info> command installs Project.
EOT
            )
        ;
    }

    /**
     * {@inheritdoc}
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $output->writeln('<info>Installing Project...</info>');
        $output->writeln('');

        $output->writeln('<comment>Step 1 of 4.</comment> <info>Checking system requirements.</info>');
        $this->commandExecutor->runCommand('dos:install:check-requirements', array(), $output);
        $output->writeln('');

        $output->writeln('<comment>Step 2 of 4.</comment> <info>Setting up the database.</info>');
        $this->commandExecutor->runCommand('dos:install:database', array(), $output);
        $output->writeln('');

        $output->writeln('<comment>Step 3 of 4.</comment> <info>Configuration.</info>');
        //$this->commandExecutor->runCommand('dos:install:setup', array(), $output);
        $output->writeln('');

        $output->writeln('<comment>Step 4 of 4.</comment> <info>Installing assets.</info>');
        $this->commandExecutor->runCommand('dos:install:assets', array(), $output);
        $output->writeln('');

        $map = array(
            Cernel::ENV_DEV => '/app_dev.php',
            Cernel::ENV_TEST => '/app_test.php',
            Cernel::ENV_STAGING => '/app_staging.php',
            Cernel::ENV_PROD => '/'
        );

        $output->writeln('<info>Sylius has been successfully installed.</info>');
        $output->writeln(sprintf('You can now open your store at the following path under the website root: <info>%s.</info>', $map[$this->getEnvironment()]));
    }
}
