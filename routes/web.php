<?php

use App\Controller\HomeController;
use App\Controller\PostsController;

return [
    ['GET', 'x', [HomeController::class, 'index']],
    ['GET', 'posts/{id:\d+}', [PostsController::class, 'show']]
];