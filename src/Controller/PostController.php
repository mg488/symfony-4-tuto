<?php

namespace App\Controller;

use App\Entity\Advert;
use cebe\markdown\Markdown;
use App\Helpers\MarkDownHelpers;
use App\Repository\PostRepository;
use App\Repository\AdvertRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Response;
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
   
    public function test(AdvertRepository $repoAdvert, EntityManagerInterface $em)
    { 
        $adverts =  $repoAdvert->myFind();
            // dd($adverts);
        $count =  $repoAdvert->myCount();
        // dd((int)$count); 
        $listAdvertsApplications=$repoAdvert->getAdvertWithApplications();
        // dd($listAdvertsApplications);
        // $listAdvertCategories = $repoAdvert->getAdvertWithCategoris(array('Graphisme','Réseau'));

        // dd($listAdvertCategories);
        $dernierCatAvecAnnonce=$repoAdvert->getApplicationsWithAdvert(2);
        // dd($dernierCatAvecAnnonce);
        $advert = new Advert();
        $advert->setTitle("Recherche développeur !");
        $advert->setAuthor("Lima");
        // $advert->setSlug("Recherche développeur !");
        $advert->setContent('Nous recherchons un développeur CMS débutant sur Lyon. bien paye avec tous les avantages qu\'il faut');

        $em->persist($advert);
        // $em->flush(); // C'est à ce moment qu'est généré le slug

        return new Response('Slug généré : '.$advert->getSlug());
  // Affiche « Slug généré : recherche-developpeur »
        // $listAdvertCategoriesApplications = $repoAdvert->getLisAdvertCategoryApplications(12);
        // dd($listAdvertCategoriesApplications);

        // return $this->render('post/test.html.twig',['adverts'=>$adverts,
        //                                             'listAdvertsApplications'=>$listAdvertsApplications]);
    }
}
