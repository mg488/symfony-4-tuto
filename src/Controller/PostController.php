<?php

namespace App\Controller;

use cebe\markdown\Markdown;
use App\Helpers\MarkDownHelpers;
use App\Repository\PostRepository;
use App\Repository\AdvertRepository;
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
   
    public function test(AdvertRepository $repoAdvert)
    { 
        $adverts =  $repoAdvert->myFind();
            // dd($adverts);
        $count =  $repoAdvert->myCount();
        // dd((int)$count); 
        $listAdvertsApplications=$repoAdvert->getAdvertWithApplications();
        // dd($listAdvertsApplications);
        foreach($listAdvertsApplications as $listAdApp)
        {
            // dd($listAdApp);
            // dd($listAdApp->getApplications()->getValues());
        }
        return $this->render('post/test.html.twig',['adverts'=>$adverts,
                                                    'listAdvertsApplications'=>$listAdvertsApplications]);
    }
}
