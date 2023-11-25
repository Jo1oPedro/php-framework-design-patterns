<?php

$dotenv = new \Symfony\Component\Dotenv\Dotenv();

$dotenv->load(BASE_PATH . "/.env");

$container = new \League\Container\Container();

$container->delegate(new \League\Container\ReflectionContainer(true));

## PARAMETERS
include BASE_PATH . "/routes/web.php";
$appEnv = $_SERVER['APP_ENV'];

$container->add("APP_ENV", new \League\Container\Argument\Literal\StringArgument($appEnv));

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

return $container;