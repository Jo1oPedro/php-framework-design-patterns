<?php

namespace App\Controller;

use App\Entity\Post;
use App\Repository\PostMapper;
use App\Repository\PostRepository;
use Cascata\Framework\Controller\AbstractController;
use Cascata\Framework\Http\RedirectResponse;
use Cascata\Framework\Http\Request;
use Cascata\Framework\Http\Response;


class PostsController extends AbstractController
{
    public function __construct(
        private PostMapper $postMapper,
        private PostRepository $postRepository
    )
    {

    }

    public function show(Request $request, int $id): Response
    {
        $post = $this->postRepository->findOrFail($id);
        return $this->render('teste.php', ["post" => $post]);
        return $this->renderTwig('posts.html.twig', [
            //"postId" => "<script>alert('you\'ve benn hacked')</script>"//$id
            //"postId" => $id
            "post" => $post
        ]);
    }

    public function create(): Response
    {
        return $this->renderTwig('create-post.html.twig');
    }

    public function store(Request $request): Response
    {
        $variables = $request->all();

        $post = Post::create($variables->title, $variables->body);

        $this->postMapper->save($post);

        return new RedirectResponse('/posts');
    }
}