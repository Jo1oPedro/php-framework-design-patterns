<?php

/* Esse arquivo index.php serve como um front controller, ou um arquivo de acesso
global para a nossa aplicação. O próprio .htaccess redireciona as requisições para cá
*/
declare(strict_types=1);

use Cascata\Framework\Http\Kernel;
use Cascata\Framework\Http\Request;

define('BASE_PATH', dirname(__DIR__));

require_once BASE_PATH . "/vendor/autoload.php";

$container = require BASE_PATH . "/config/services.php";

// request received
$request = Request::createFromGlobals();

$kernel = $container->get(Kernel::class);

// send response (string of content)
$response = $kernel->handle($request);
$response->send();