<?php

namespace App\Controller;

use Cascata\Framework\Http\Response;

class HomeController
{
    public function __construct(
        private readonly PostsController $postsController,
        private string $dale = "1"
    ) {
        //dd($dale);
        //dd($postsController);
    }

    public function index(): Response
    {
        $content = '<h1>Hello world</h1>';

        return new Response($content);
    }
}