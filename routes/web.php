<?php

use App\Controller\HomeController;
use App\Controller\PostsController;
use Cascata\Framework\Http\Response;
use Cascata\Framework\Routing\RouteGrouper;

$routeGrouper = new RouteGrouper();

$routeGrouper->addRoute('GET', '/', [HomeController::class, 'index']);
$routeGrouper->addRoute('GET', '/x2', [HomeController::class, 'index2']);
$routeGrouper->addRoute('GET', '/posts/{id:\d+}', [PostsController::class, 'show']);
$routeGrouper->addRoute('POST', 'post/{id:\d+}', [PostsController::class, 'show']);
$routeGrouper->addRoute('GET', '/posts', [PostsController::class, 'create']);
$routeGrouper->addRoute('POST', '/posts', [PostsController::class, 'store']);
$routeGrouper->addRoute('GET', 'post/{id:\d+}', function($id) {
    return new Response("este é o id: " . $id);
});
$routeGrouper->addRoute('POST', 'teste', [PostsController::class, 'show']);
$routeGrouper->addRoute('GET', '/x', function () {
    $post = new \Database\Factories\PostFactory();
    dd($post->count(2)->create());
});
/*return [
    ['GET', 'x', [HomeController::class, 'index']],
    ['GET', 'posts/{id:\d+}', [PostsController::class, 'show']],
    ['POST', 'post/{id:\d+}', [PostsController::class, 'show']],
];*/


// para rodar o projeto: php -S localhost:8080 -t public public/index.php