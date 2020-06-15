<?php

namespace App\Controller;

use App\Entity\Image;
use App\Entity\Advert;
use App\Entity\Category;
use App\Entity\AdvertSkill;
use App\Entity\Application;
use App\Service\AntispamService;
use Symfony\Component\Mime\Email;
use App\Repository\ImageRepository;
use App\Repository\SkillRepository;
use App\Repository\AdvertRepository;
use App\Repository\CategoryRepository;
use Doctrine\ORM\EntityManagerInterface;
use App\Repository\AdvertSkillRepository;
use App\Repository\ApplicationRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class AdvertController extends AbstractController
{
    
    // public function sendEmail(MailerInterface $mailer){
    //     $happyMessage = '1er email send of gm in symfony';

    //     $email = (new Email())
    //         ->from('contactvillgedenguith@gmail.com')
    //         ->to('contactvillgedenguith@gmail.com')
    //         ->subject('Site update just happened!')
    //         ->text('Someone just updated the site. We told them: '.$happyMessage);

    //     $mailer->send($email);
    // }
    public function contactAction(){
        $this->addFlash('info','La page decontact n\'est pas encore disponible');
        
        $this->addFlash('info','elle sera bientôt mise en place');
        return $this->render('advert/contact.html.twig');
        // return $this->redirectToRoute('advert_contact');

    }
    public function menuAction(AdvertRepository $repoAdvert ) : Response //Optimisé=Okay
    {
      
      $listAdverts = $repoAdvert->getAdvertMenu(3);
      return $this->render('advert/menu.html.twig', array(
        // Tout l'intérêt est ici : le contrôleur passe // les variables nécessaires au template menu.html.twig!
        'listAdverts' => $listAdverts
      ));
    }
    // /*********************index* la page indexAdvert.html.twig******************************************/ //
    public function index($page, AdvertRepository $repo) : Response //Optimisé=Okay
    {
        $listAdverts = $repo->findAll(); //myFindAll(); : developpé à la main
        if((int)$page < 1){
            throw$this->createNotFoundException('Page "'.$page.'"inexistante');
        }
        return $this->render('advert/indexAdvert.html.twig', array(
            'listAdverts' => $listAdverts
          ));
    }
    // /*********************views**okay******************************************/ //
    public function view($id, advertRepository $repo,AdvertSkillRepository $repoAdvertSkill) :Response //Optimisé=Okay
    {
      //*****On récupère pour l'annonce $id : l'image, les catéfories et les applications associées (queryBuilder)****\
      $advert = new Advert(['id'=>$id]);
      $listAdvImgCatApp = $repo->getAdvImgCategoriesApplications($id);
      if (null === $listAdvImgCatApp) {
        throw new NotFoundHttpException("L'annonce d'id ".$id." n'existe pas.");
      }
      //****pour l'id de l'annonce, allez chercher les skills associées*****\
      $listAdvertSkills = $repoAdvertSkill->getAdvtSkils($id);
      return $this->render('advert/viewAdvert.html.twig', array(
       'listAdvImgCatApp'=>$listAdvImgCatApp[0],
        'listAdvertSkills' => $listAdvertSkills
      ));
    }

    // /*********************add********************************************/ //
    public function add(Request $request, AntispamService $antispam,SkillRepository $repoSkill, EntityManagerInterface $em) :Response
    {
            // Création de l'entité Advert
            $text='Nous recherchons un développeur CMS débutant sur Lyon. bien paye avec tous les avantages qu\'il faut';
            $advert = new Advert();
            $advert->setTitle('Recherche CMS');
            $advert->setAuthor('bass.momoLyon@gmail.com');
            $advert->setContent($text);
           
            // Création d'une première candidature
            $application1 = new Application();
            $application1->setAuthor('Pathé');
            $application1->setContent("Je suis super motivé etprêt à faire le job.");

            // Création d'une deuxième candidature par exemple
            $application2 = new Application();
            $application2->setAuthor('Aurélie');
            $application2->setContent("Je suis très motivée.");
              // On lie les candidatures à l'annonce
            $application1->setAdvert($advert);
            $application2->setAdvert($advert);
            
            // Doctrine ne connait pas encore l'entité $advert. Si vous n'avez pas défini la relation AdvertSkill
            // avec un cascade persist (ce qui est le cas si vous avez utilisé mon code), alors on doit persister $advert
            $em->persist($advert);
            $em->persist($application1);
            $em->persist($application2);

            // $em->flush();
             //******Étape 2 : On persiste et on « flush » tout ce qui a été persisté avant
            
            if($request->isMethod('POST'))
            {
              $this->addFlash('notice', 'Annonce bien enregistrée.');
              return $this->redirectToRoute('advert_view', array('id' =>$advert->getId()));
            }

            return $this->render('advert/addAdvert.html.twig');
    }
    // /*********************edit********************************************/ //
    public function edit($id, Request $request,ImageRepository $repoImg ,AdvertRepository $repo,CategoryRepository $repoCat,EntityManagerInterface $em) :Response
    {
        $advert = new Advert();
        $advert = $repo->findOneBy(['id'=>$id]);
        
        // $em->flush();
        return $this->render('advert/editAdvert.html.twig',array(
            'advert' => $advert
          ));
    }
    public function delete($id, AdvertRepository $repo,EntityManagerInterface $em) : Response //Optimisé=Okay
    {
        $advert=$repo->find($id);
        if($advert === null)
        {
          throw NotFoundHttpException('\'annonce avec l\'id : '.$id .'n\'existe pas');
        }
        foreach($advert->getCategories() as $category)
        {
          $advert->removeCategory($category);
        }
        $em->flush();
        return $this->redirectToRoute('advert_index');    
      }
}
