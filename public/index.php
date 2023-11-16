<?php

/* Esse arquivo index.php serve como um front controller, ou um arquivo de acesso
global para a nossa aplicação. O próprio .htaccess redireciona as requisições para cá
*/
declare(strict_types=1);

use Cascata\Framework\Http\Request;
use Cascata\Framework\Http\Response;

require_once __DIR__ . "/../vendor/autoload.php";

// request received
$request = Request::createFromGlobals();
//dd($request);

// perform some logic

// send response (string of content)
$content = '<h1>Hello world</h1>';

$response = new Response(content: $content, status: 200, headers: []);

$response->send();