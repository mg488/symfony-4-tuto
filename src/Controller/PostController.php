<?php

namespace App\Controller;

use cebe\markdown\Markdown;
use App\Helpers\MarkDownHelpers;
use App\Repository\PostRepository;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class PostController extends AbstractController
{
     
    public function index(PostRepository $repository, MarkDownHelpers $mkdHelpers)
    {   
        $posts = $repository->findAll();
        $parsedPosts =$mkdHelpers->parse($posts);
        return $this->render('post/index.html.twig', [
            'posts' => $parsedPosts
        ]);
    }
   
    public function test()
    { 
        
        return $this->render('post/test.html.twig');
    }
}
