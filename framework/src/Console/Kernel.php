<?php

namespace Cascata\Framework\Console;

use Cascata\Framework\Console\Command\CommandInterface;
use Psr\Container\ContainerInterface;

final class Kernel
{
    public function __construct(
        private ContainerInterface $container,
        private Application $application
    ) {}

    public function handle(): int
    {
        // Register application commands with the container
        $this->registerCommands(__DIR__ . "/Command", 'base-commands-namespace');

        // Register developer commands with the container
        $this->registerCommands($this->container->get('BASE_PATH') . "/src/Console/Command", 'base-developer-commands-namespace');

        // Run the console application, returning a status code
        $status = $this->application->run();
        // Return the status code
        return $status;
    }

    private function registerCommands(string $namespace, string $containerId): void
    {
        // === Register all built in commands ===
        // Get all files in the commands dir
        $commandFiles = new \DirectoryIterator($namespace);
        $namespace = $this->container->get($containerId);

        // loop over all files in the commands folder
            /** @var \DirectoryIterator $commandFile */
        foreach($commandFiles as $commandFile) {
            if(!$commandFile->isFile()) {
                continue;
            }

            $command = $namespace.pathinfo($commandFile, PATHINFO_FILENAME);

            if(is_subclass_of($command, CommandInterface::class)) {
                // if it is a subclass of command interface
                $commandName = (new \ReflectionClass($command))
                    ->getProperty('name')
                    ->getDefaultValue();

                $this->container->add($commandName, $command);
                // Add to the container, using the name as the id, example:
                // $container->add('database:migrations:migrate', MigrateDatabase::class)
            }
            // Get the command class name.. using psr4 this will be same as filename
        }

        // === Register all user-defined commands(@todo) ===
    }
}