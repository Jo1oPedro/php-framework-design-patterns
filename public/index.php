<?php

/* Esse arquivo index.php serve como um front controller, ou um arquivo de acesso
global para a nossa aplicação. O próprio .htaccess redireciona as requisições para cá
*/
declare(strict_types=1);

use Cascata\Framework\Http\Kernel;
use Cascata\Framework\Http\Request;
use Cascata\Framework\Http\Response;

define('BASE_PATH', dirname(__DIR__));

require_once __DIR__ . "/../vendor/autoload.php";

// request received
$request = Request::createFromGlobals();

// perform some logic
$kernel = new Kernel();

// send response (string of content)
$response = $kernel->handle($request);
$response->send();