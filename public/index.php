<?php

/* Esse arquivo index.php serve como um front controller, ou um arquivo de acesso
global para a nossa aplicação. O próprio .htaccess redireciona as requisições para cá
*/
declare(strict_types=1);

use Cascata\Framework\Http\Kernel;
use Cascata\Framework\Http\Request;
use Cascata\Framework\Routing\Router;

define('BASE_PATH', dirname(__DIR__));

require_once BASE_PATH . "/vendor/autoload.php";

$container = require BASE_PATH . "/config/services.php";

dd($container);

// request received
$request = Request::createFromGlobals();

$router = new Router();

// perform some logic
$kernel = new Kernel($router);

// send response (string of content)
$response = $kernel->handle($request);
$response->send();