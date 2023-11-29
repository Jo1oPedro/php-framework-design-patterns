<?php

namespace Cascata\Framework\Console;

use Cascata\Framework\Console\Command\CommandInterface;
use Doctrine\DBAL\Connection;
use League\Container\Container;
use MongoDB\Driver\Command;
use Psr\Container\ContainerInterface;

final class Kernel
{
    public function __construct(
        private ContainerInterface $container,
        private Application $application
    ) {}

    public function handle(): int
    {
        // Register commands with the container
        $this->registerCommands();
        // Run the console application, returning a status code
        $status = $this->application->run();
        // Return the status code
        return $status;
    }

    private function registerCommands(): void
    {
        // === Register all built in commands ===
        // Get all files in the commands dir
        $commandFiles = new \DirectoryIterator(__DIR__ . "/Command");
        $namespace = $this->container->get('base-commands-namespace');

        // loop over all files in the commands folder
            /** @var \DirectoryIterator $commandFile */
        foreach($commandFiles as $commandFile) {
            if(!$commandFile->isFile()) {
                continue;
            }

            $command = $namespace.pathinfo($commandFile, PATHINFO_FILENAME);

            if(is_subclass_of($command, CommandInterface::class)) {
                // if it is a subclass of command interface
                $commandName = (new \ReflectionClass($command))->getProperty('name')->getDefaultValue();

                $this->container->add($commandName, $command);
                // Add to the container, using the name as the id, example:
                // $container->add('database:migrations:migrate', MigrateDatabase::class)
            }
            // Get the command class name.. using psr4 this will be same as filename
        }

        // === Register all user-defined commands(@todo) ===
    }
}