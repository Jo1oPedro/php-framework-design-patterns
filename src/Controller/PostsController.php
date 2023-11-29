<?php

namespace App\Controller;

use Cascata\Framework\Controller\AbstractController;
use Cascata\Framework\Http\Response;

class PostsController extends AbstractController
{
    public function show(int $id): Response
    {
        return $this->renderTwig('posts.html.twig', [
            "postId" => "<script>alert('you\'ve benn hacked')</script>"//$id
        ]);
    }

    public function create(): Response
    {
        return $this->renderTwig('create-post.html.twig');
    }
}