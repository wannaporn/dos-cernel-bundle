<?php

namespace DoS\CernelBundle\Command;

use Doctrine\DBAL\Schema\AbstractSchemaManager;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class InstallDatabaseCommand extends AbstractInstallCommand
{
    private $infos = array(
        1 => 'Creating database for environment <info>%s</info>.',
        2 => '<question>It appears that your database already exists. Would you like to reset it? (y/N)</question> ',
        3 => '<question>Seems like your database contains schema. Do you want to reset it? (y/N)</question> '
    );

    /**
     * {@inheritdoc}
     */
    protected function configure()
    {
        $this
            ->setName('dos:install:database')
            ->setDescription('Install Project database.')
            ->setHelp(<<<EOT
The <info>%command.name%</info> command creates Project database.
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
        $cmfEnabled = $this->getParameter('dos.cmf.enabled');

        if (!$this->isDatabasePresent()) {
            $commands = array(
                'doctrine:database:create',
                'doctrine:schema:create',
                'cache:clear',
            );

            if ($cmfEnabled) {
                $commands[] = 'doctrine:phpcr:repository:init';
            }

            $this->runCommands($commands, $input, $output);

            return 0;
        }

        $dialog = $this->getHelper('dialog');
        $commands = array();

        if ($input->getOption('no-interaction')) {
            $commands['doctrine:schema:update'] = array('--force' => true);
        } else {
            if ($dialog->askConfirmation($output, $this->infos[2], false)) {
                $commands['doctrine:database:drop'] = array('--force' => true);
                $commands[] = 'doctrine:database:create';
                $commands[] = 'doctrine:schema:create';
            } elseif ($this->isSchemaPresent()) {
                if ($dialog->askConfirmation($output, $this->infos[3], false)) {
                    $commands['doctrine:schema:drop'] = array('--force' => true);
                    $commands[] = 'doctrine:schema:create';
                }
            }
        }

        $commands[] = 'cache:clear';

        if ($cmfEnabled) {
            $commands[] = 'doctrine:phpcr:repository:init';
        }

        $this->runCommands($commands, $input, $output);

        $this->commandExecutor->runCommand('dos:install:sample-data', array(), $output);
    }

    /**
     * @return bool
     *
     * @throws \Exception
     */
    private function isDatabasePresent()
    {
        $databaseName = $this->getDatabaseName();

        try {
            return in_array($databaseName, $this->getSchemaManager()->listDatabases());
        } catch (\Exception $exception) {
            $message = $exception->getMessage();

            $mysqlDatabaseError = false !== strpos($message, sprintf("Unknown database '%s'", $databaseName));
            $postgresDatabaseError = false !== strpos($message, sprintf('database "%s" does not exist', $databaseName));

            if ($mysqlDatabaseError || $postgresDatabaseError) {
                return false;
            }

            throw $exception;
        }
    }

    /**
     * @return bool
     */
    private function isSchemaPresent()
    {
        $schemaManager = $this->getSchemaManager();

        return $schemaManager->tablesExist(array('ext_log_entries'));
    }

    /**
     * @return string
     */
    private function getDatabaseName()
    {
        $databaseName = $this->getContainer()->getParameter('database_name');

        if ('prod' !== $this->getEnvironment()) {
            $databaseName = sprintf('%s_%s', $databaseName, $this->getEnvironment());
        }

        return $databaseName;
    }

    /**
     * @return AbstractSchemaManager
     */
    private function getSchemaManager()
    {
        return $this->get('doctrine')->getManager()->getConnection()->getSchemaManager();
    }
}
