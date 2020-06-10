<?php

namespace App\Controller;

use App\Entity\Image;
use App\Entity\Advert;
use App\Entity\Application;
use App\Service\AntispamService;
use Symfony\Component\Mime\Email;
use App\Repository\ImageRepository;
use App\Repository\AdvertRepository;
use App\Repository\CategoryRepository;
use Doctrine\ORM\EntityManagerInterface;
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
    public function menuAction()
    {
      // On fixe en dur une liste ici, bien entendu par la suite
      // on la récupérera depuis la BDD !
      $listAdverts = array(
        array('id' => 1, 'title' => 'Recherche développeur Symfony'),
        array('id' => 2, 'title' => 'Mission de webmaster'),
        array('id' => 3, 'title' => 'Offre de stage webdesigner'),
        array('id' => 4, 'title' => 'Developpeur JAVA')
      );
  
      return $this->render('advert/menu.html.twig', array(
        // Tout l'intérêt est ici : le contrôleur passe // les variables nécessaires au template menu.html.twig!
        'listAdverts' => $listAdverts
      ));
    }
    // /*********************index********************************************/ //
    public function index($page, AdvertRepository $repo) : Response
    {
        $listAdverts = $repo->findAll(); 
        if($page < 1){
            throw$this->createNotFoundException('Page "'.$page.'"inexistante');
        }
        return $this->render('advert/indexAdvert.html.twig', array(
            'listAdverts' => $listAdverts
          ));
    }
    // /*********************views********************************************/ //
    public function view($id, advertRepository $repo,ApplicationRepository $repApp, EntityManagerInterface $em) :Response
    {

      // On récupère l'annonce $id
      $advert = new Advert();
      $advert = $repo->findOneBy(['id'=>$id]);
      // dd($advert);
  
      if (null === $advert) {
        throw new NotFoundHttpException("L'annonce d'id ".$id." n'existe pas.");
      }
      
      // On récupère la liste des candidatures de cette annonce
      $listApplications = $repApp->findBy(array('advert' => $advert));
      // dd($listApplications);

      return $this->render('advert/viewAdvert.html.twig', array(
        'advert'           => $advert,
        'listApplications' => $listApplications
      ));
    }

    // /*********************add********************************************/ //
    public function add(Request $request, AntispamService $antispam, EntityManagerInterface $em) :Response
    {
            // Création de l'entité Advert
            $text='Nous recherchons un développeur Symfony débutant sur Lyon. bien paye avec tous les avantages qu\'il faut';
            $advert = new Advert();
            $advert->setTitle('Recherche développeur Symfony.');
            $advert->setAuthor('Alexandre');
            $advert->setContent($text);

            // Création d'une première candidature
            $application1 = new Application();
            $application1->setAuthor('Marine');
            $application1->setContent("J'ai toutes les qualités requises.");

            // Création d'une deuxième candidature par exemple
            $application2 = new Application();
            $application2->setAuthor('Pierre');
            $application2->setContent("Je suis très motivé.");

            $application3 = new Application();
            $application3->setAuthor('Babacar');
            $application3->setContent("Je suis très hyper motivé.");

            // On lie les candidatures à l'annonce
            $application1->setAdvert($advert);
            $application2->setAdvert($advert);
            $application3->setAdvert($advert);

            // Étape 1 : On « persiste » l'entité
            $em->persist($advert);

            // Étape 1 ter : pour cette relation pas de cascade lorsqu'on persiste Advert, car la relation est
            // définie dans l'entité Application et non Advert. On doit donc tout persister à la main ici.
            $em->persist($application1);
            $em->persist($application2);
            $em->persist($application3);

            // Étape 2 : On « flush » tout ce qui a été persisté avant
            // $em->flush();

            if ($antispam->isSpam($text)) {
                $infoMessage = 'Votre message a été détecté comme spam !';
                return $this->render('advert/spam.html.twig',['infoMessage'=>$infoMessage]);
            }
             //******Étape 2 : On persiste et on « flush » tout ce qui a été persisté avant
            
            if($request->isMethod('POST')){
             
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
       
        if($advert === null)
        {
          throw new NotFoundHttpException("L'annonce d'id ".$id." n'existe pas !");
        }
        $listeCategories = $repoCat->findAll();
        foreach($listeCategories as $category)
        {
          $advert->addCategory($category);
        }
        $em->persist($advert);
        $em->flush();
        return $this->render('advert/editAdvert.html.twig',array(
            'advert' => $advert
          ));
    }
    public function delete($id, AdvertRepository $repo,EntityManagerInterface $em) :Response
    {
        $advert=$repo->findOneBy(['id'=>$id]);
        $em->remove($advert);
        $em->flush();
        return $this->redirectToRoute('advert_index');    
      }
}
