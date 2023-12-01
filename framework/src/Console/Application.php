<?php

namespace Cascata\Framework\Console;

use Cascata\Framework\Console\Command\CommandInterface;
use Cascata\Framework\Console\Exceptions\ConsoleException;
use Psr\Container\ContainerInterface;

class Application
{
    public function __construct(
        private ContainerInterface $container
    ) {}

    public function run(): int
    {
        // Use environment variables to obtain the command name
        $argv = $_SERVER['argv'];
        $commandName = $argv[1] ?? null;

        // Throw an exception if no command name is provided
        if(is_null($commandName)) {
            throw new ConsoleException('O nome do comando deve ser fornecido');
        }
        // Use command name to obtain a command object from the container
        /** @var CommandInterface $command */
        $command = $this->container->get($commandName);

        // Parse variables to obtain options and args
        $arguments = array_slice($argv, 2);

        $options = $this->parseOptions($arguments);

        // Execute the command, return the status code
        $options = array_merge(['BASE_PATH' => $this->container->get('BASE_PATH')], $options);
        $status = $command->execute($options);

        // Return the status code
        return $status;
    }

    private function parseOptions(array $arguments): array
    {
        $options = [];
        foreach ($arguments as $argument) {
            if(str_starts_with($argument, '--')) {
                $option = explode('=', substr($argument, 2));
                $options[$option[0]] = $option[1] ?? true;
            }
        }
        return $options;
    }
}