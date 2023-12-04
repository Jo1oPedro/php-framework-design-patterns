<?php

namespace App\Controller;

use Cascata\Framework\Controller\AbstractController;
use Cascata\Framework\Http\Request;
use Cascata\Framework\Http\Response;

class PostsController extends AbstractController
{
    public function show(int $id): Response
    {
        return $this->renderTwig('posts.html.twig', [
            //"postId" => "<script>alert('you\'ve benn hacked')</script>"//$id
            "postId" => $id
        ]);
    }

    public function create(): Response
    {
        return $this->renderTwig('create-post.html.twig');
    }

    public function store(): void
    {
        dd($this->request->postParams);
    }
}