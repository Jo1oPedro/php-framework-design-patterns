<?php

use App\Controller\HomeController;
use App\Controller\PostsController;
use Cascata\Framework\Routing\RouteGrouper;

$routeGrouper = new RouteGrouper();

$routeGrouper->addRoute('GET', 'x', [HomeController::class, 'index']);
$routeGrouper->addRoute('GET', 'posts/{id:\d+}', [PostsController::class, 'show']);
$routeGrouper->addRoute('POST', 'post/{id:\d+}', [PostsController::class, 'show']);

/*return [
    ['GET', 'x', [HomeController::class, 'index']],
    ['GET', 'posts/{id:\d+}', [PostsController::class, 'show']],
    ['POST', 'post/{id:\d+}', [PostsController::class, 'show']],
];*/