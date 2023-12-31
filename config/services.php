<?php

$dotenv = new \Symfony\Component\Dotenv\Dotenv();

$dotenv->load(BASE_PATH . "/.env");

$container = \Cascata\Framework\GlobalContainer\Container::getInstance();//new \League\Container\Container();

$container->delegate(new \League\Container\ReflectionContainer(true));

## PARAMETERS
include BASE_PATH . "/routes/web.php";
$symfonyDotEnvVars = explode(",", $_SERVER['SYMFONY_DOTENV_VARS']);
//$appEnv = $_SERVER['APP_ENV'];
$templatesPath = BASE_PATH . "/templates";

$container->add("BASE_PATH", new \League\Container\Argument\Literal\StringArgument(BASE_PATH));

foreach($symfonyDotEnvVars as $symfonyDotEnvVar) {
    //$container->add("APP_ENV", new \League\Container\Argument\Literal\StringArgument($appEnv));
    $container->add($symfonyDotEnvVar, new \League\Container\Argument\Literal\StringArgument($_SERVER[$symfonyDotEnvVar]));
}

//$databaseUrl = 'sqlite:///' . BASE_PATH . '/var/db.sqlite';
$databaseUrl = 'mysql://root:@127.0.0.1:3306/codejr';

$container->add(
    'base-commands-namespace',
    new \League\Container\Argument\Literal\StringArgument('Cascata\\Framework\\Console\\Command\\')
);

$container->add(
    'base-developer-commands-namespace',
    new \League\Container\Argument\Literal\StringArgument('App\\Console\\Command\\')
);

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

$container->add(\Cascata\Framework\Console\Kernel::class)
    ->addArguments([$container, \Cascata\Framework\Console\Application::class]);

$container->add(\Cascata\Framework\Console\Application::class)
    ->addArgument($container);

/*$container->addShared('filesystem-loader', \Twig\Loader\FilesystemLoader::class)
    ->addArgument(new \League\Container\Argument\Literal\StringArgument($templatesPath));

$container->addShared('twig', \Twig\Environment::class)
    ->addArgument('filesystem-loader');*/

$container->addShared(
    \Cascata\Framework\Session\SessionInterface::class,
    \Cascata\Framework\Session\Session::class
);

$container->add('template-renderer-factory', \Cascata\Framework\Template\TwigFactory::class)
    ->addArguments([
        \Cascata\Framework\Session\SessionInterface::class,
        new \League\Container\Argument\Literal\StringArgument($templatesPath)
    ]);

$container->addShared('twig', function () use ($container) {
    return $container->get('template-renderer-factory')->create();
});

$container->add(\Cascata\Framework\Controller\AbstractController::class);

$container->inflector(\Cascata\Framework\Controller\AbstractController::class)
    ->invokeMethod('setContainer', [$container]);

$container->add(\Cascata\Framework\Dbal\ConnectionFactory::class)
    ->addArguments([
        new \League\Container\Argument\Literal\StringArgument($databaseUrl)
    ]);

$container->addShared(\Doctrine\DBAL\Connection::class, function() use($container): \Doctrine\DBAL\Connection {
    $connection = $container->get(\Cascata\Framework\Dbal\ConnectionFactory::class)->create();
    //$connection->setAutoCommit(false);
    return $connection;
});

$container->add(
    'database:migrations:migrate',
    \Cascata\Framework\Console\Command\MigrateDatabase::class
)->addArguments([
    \Doctrine\DBAL\Connection::class,
    new \League\Container\Argument\Literal\StringArgument(BASE_PATH . '/database/migrations')
]);

$container->add(
    'database:migrations:rollback',
    \Cascata\Framework\Console\Command\RollbackDatabase::class
)->addArguments([
    \Doctrine\DBAL\Connection::class,
    new \League\Container\Argument\Literal\StringArgument(BASE_PATH . "/database/migrations")
]);

$container->addShared(
    \Cascata\Framework\Http\Request::class,
    \Cascata\Framework\Http\Request::createFromGlobals()
);

$container->inflector(\Cascata\Framework\Request\FormRequest::class)
    ->invokeMethod(
        'validateRequest',
        [$container->get(\Cascata\Framework\Http\Request::class)]
    );

return $container;