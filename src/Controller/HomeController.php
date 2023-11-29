<?php

namespace App\Controller;

use Cascata\Framework\Controller\AbstractController;
use Cascata\Framework\Http\Response;
use Twig\Environment;

class HomeController extends AbstractController
{
    public function __construct(
        private readonly PostsController $postsController,
    ) {
    }

    public function index(): Response
    {
        $name = 'cascata';
        return $this->render("teste.php", [
            'name' => $name
        ]);
    }

    public function index2(): Response
    {
        $name = 'cascata';
        return $this->renderTwig("home.html.twig", [
            'name' => $name
        ]);
    }
}