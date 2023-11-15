<?php

/* Esse arquivo index.php serve como um front controller, ou um arquivo de acesso
global para a nossa aplicação. O próprio .htaccess redireciona as requisições para cá
*/
declare(strict_types=1);

require_once __DIR__ . "/../vendor/autoload.php";

// request received
$request = \Cascata\Framework\Http\Request::createFromGlobals();

dd($request);

// perform some logic

// send response (string of content)
echo 'Hello world 2';