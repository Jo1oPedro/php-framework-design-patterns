<?php

namespace App\Controller;

use App\helpers\Helper;
use Cascata\Framework\Controller\AbstractController;
use Cascata\Framework\Http\Request;
use Cascata\Framework\Http\Response;


class PostsController extends AbstractController
{
    private Helper $helper;
    public function __construct(Helper $helper)
    {
        $this->helper = $helper;
    }

    public function show(Request $request, int $id): Response
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

    public function store(Request $request, int $id): void
    {
        //dd($this->request);
    }
}