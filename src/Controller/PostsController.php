<?php

namespace App\Controller;

use Cascata\Framework\Controller\AbstractController;
use Cascata\Framework\Http\Response;

class PostsController extends AbstractController
{
    public function show(int $id): Response
    {
        return $this->renderTwig('posts.html.twig', [
            "postId" => $id
        ]);
    }
}