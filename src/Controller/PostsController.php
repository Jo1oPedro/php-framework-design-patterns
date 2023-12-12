<?php

namespace App\Controller;

use App\Entity\Post;
use App\Repository\PostMapper;
use App\Repository\PostRepository;
use App\Requests\PostsCreateRequest;
use Cascata\Framework\Controller\AbstractController;
use Cascata\Framework\Http\RedirectResponse;
use Cascata\Framework\Http\Request;
use Cascata\Framework\Http\Response;
use Cascata\Framework\Session\Session;


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
        session()->set('teste', 'dale1234');
        $post = $this->postRepository->findOrFail($id);
        return render('teste.php', ["post" => $post]);
        return $this->render('teste.php', ["post" => $post]);
        return $this->renderTwig('posts.php.twig', [
            //"postId" => "<script>alert('you\'ve benn hacked')</script>"//$id
            //"postId" => $id
            "post" => $post
        ]);
    }
    
    public function create(): Response
    {
        return render('create-post.php');
        //return $this->render('create-post.php');
        //return $this->renderTwig('create-post.html.twig');
    }

    public function store(Request $request, PostsCreateRequest $postRequest): Response
    {
        $variables = $request->all();

        $post = Post::create($variables->title, $variables->body);

        $this->postMapper->save($post);

        //return new RedirectResponse('/posts');
        return redirect('/posts')->with('success', true);
        //return redirect('/posts')->withFlash('success', true);
    }
}