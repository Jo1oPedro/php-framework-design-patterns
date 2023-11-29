<?php

$dotenv = new \Symfony\Component\Dotenv\Dotenv();

$dotenv->load(BASE_PATH . "/.env");

$container = new \League\Container\Container();

$container->delegate(new \League\Container\ReflectionContainer(true));

## PARAMETERS
include BASE_PATH . "/routes/web.php";
$appEnv = $_SERVER['APP_ENV'];
$templatesPath = BASE_PATH . "/templates";

$container->add("APP_ENV", new \League\Container\Argument\Literal\StringArgument($appEnv));

$databaseUrl = 'sqlite:///' . BASE_PATH . '/var/db.sqlite';

## SERVICES
/**
 * Esse bloco corresponde a autowiring de uma Router responsável pelo processamento das rotas
 * e equivalencia da request atual com alguma rota sendo inserida como argumento automaticamente na criação
 * de um kernel responsável pelo processamento da request.
 */
$container->add(
    \Cascata\Framework\Routing\RouterInterface::class,
    \Cascata\Framework\Routing\Router::class);

/** @var \Cascata\Framework\Routing\RouteGrouper $routeGrouper */
$container->extend(\Cascata\Framework\Routing\RouterInterface::class)
    ->addMethodCall('setRoutes', [$routeGrouper]);

$container->add(\Cascata\Framework\Http\Kernel::class)
    ->addArgument(\Cascata\Framework\Routing\RouterInterface::class)
    ->addArgument($container);
/** */

$container->addShared('filesystem-loader', \Twig\Loader\FilesystemLoader::class)
    ->addArgument(new \League\Container\Argument\Literal\StringArgument($templatesPath));

$container->addShared('twig', \Twig\Environment::class)
    ->addArgument('filesystem-loader');

$container->add(\Cascata\Framework\Controller\AbstractController::class);

$container->inflector(\Cascata\Framework\Controller\AbstractController::class)
    ->invokeMethod('setContainer', [$container]);

$container->add(\Cascata\Framework\Dbal\ConnectionFactory::class)
    ->addArguments([
        new \League\Container\Argument\Literal\StringArgument($databaseUrl)
    ]);

$container->addShared(\Doctrine\DBAL\Connection::class, function() use($container): \Doctrine\DBAL\Connection {
    return $container->get(\Cascata\Framework\Dbal\ConnectionFactory::class)->create();
});

return $container;