<?php

namespace App\Controller;

use App\Entity\User;
use App\Entity\Advert;
use cebe\markdown\Markdown;
use Doctrine\ORM\EntityManager;
use App\Helpers\MarkDownHelpers;
use App\Repository\PostRepository;
use App\Repository\AdvertRepository;
use FOS\UserBundle\Model\UserManager;
use Doctrine\ORM\EntityManagerInterface;
use FOS\UserBundle\Model\UserManagerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Mime\Message;

class PostController extends AbstractController
{

    // public function index(PostRepository $repository, MarkDownHelpers $mkdHelpers)
    // {
    //     $posts = $repository->findAll();
    //     $parsedPosts =$mkdHelpers->parse($posts);
    //     return $this->render('post/index.html.twig', [
    //         'posts' => $parsedPosts
    //     ]);
    // }

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
        $advert->setTitle("Recherche Manager !");
        $advert->setAuthor("Lima");
        // $advert->setSlug("Recherche développeur !");
        $advert->setContent('Nous recherchons un développeur CMS débutant sur Lyon. bien paye avec tous les avantages qu\'il faut');

        $em->persist($advert);
        // $em->flush(); // C'est à ce moment qu'est généré le slug

        return new Response('Slug généré : '.$advert->getSlug());
        // Affiche « Slug généré : Recherche-Manager »
        // $listAdvertCategoriesApplications = $repoAdvert->getLisAdvertCategoryApplications(12);
        // dd($listAdvertCategoriesApplications);

        // return $this->render('post/test.html.twig',['adverts'=>$adverts,
        //                                             'listAdvertsApplications'=>$listAdvertsApplications]);
    }
    public function userListManager(UserManagerInterface $userManager, EntityManagerInterface $em)
    {

        $user = $userManager->createUser();

        $user->setUsername('test');
        $user->setUsernameCanonical('test');

        $user->setRoles(array('ROLE_ADMIN'));
        $user->setEmail('test@gmail.com');
        $user->setEmailCanonical('test@gmail.com');

        $user->setPassword('test');
        $user->setEnabled(true);
        // $em->persist($user);
        // $em->flush();
        $users = $userManager->findUsers();
        return $this->render('user/listUser.html.twig',['users'=>$users]);
    }
    public function userUpdate(UserManagerInterface $userManager)
    {
        $user = $userManager->createUser();
        $user = $userManager->findUserBy(array('username' => 'mg'));
        $user->setRoles(array('ROLE_SUPER_ADMIN'));

        $userManager->updateUser($user);

        $this->addflash('info', 'enregistement ok');
        return $this->render('user/messageInfoUser.html.twig');
    }
    public function userCreate(UserManagerInterface $userManager)
    {
        $user = $userManager->createUser();
        // 0$user->setUsername('');


        $this->addflash('info', 'Enregistrement reussi !');
        $users = $userManager->findUsers();
        return $this->render('user/listUser.html.twig',['users'=>$users]);;
    }
}
