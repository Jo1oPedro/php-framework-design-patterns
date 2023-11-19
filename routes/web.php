<?php

use App\Controller\HomeController;
use App\Controller\PostsController;
use Cascata\Framework\Http\Response;
use Cascata\Framework\Routing\RouteGrouper;

$routeGrouper = new RouteGrouper();

$routeGrouper->addRoute('GET', '/x', [HomeController::class, 'index']);
$routeGrouper->addRoute('GET', 'posts/{id:\d+}', [PostsController::class, 'show']);
$routeGrouper->addRoute('POST', 'post/{id:\d+}', [PostsController::class, 'show']);
$routeGrouper->addRoute('GET', 'post/{id:\d+}', function($id) {
    return new Response("este é o id: " . $id);
});
$routeGrouper->addRoute('POST', 'teste', [PostsController::class, 'show']);

/*return [
    ['GET', 'x', [HomeController::class, 'index']],
    ['GET', 'posts/{id:\d+}', [PostsController::class, 'show']],
    ['POST', 'post/{id:\d+}', [PostsController::class, 'show']],
];*/


// para rodar o projeto: php -S localhost:8080 -t public public/index.php