<?php

namespace App\Controller;

use App\Entity\Image;
use App\Entity\Advert;
use App\Entity\Category;
use App\Form\AdvertType;
use App\Entity\AdvertSkill;
use App\Entity\Application;
use App\Entity\ContactByMail;
use App\Form\AdvertEditType;
use App\Form\ContactByMailType;
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
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\FormType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class AdvertController extends AbstractController
{
    
    public function sendEmail(){
       
      return new Response('dans la gestion de emails');
    }
    public function contactAction(){
        // $this->addFlash('info','La page decontact n\'est pas encore disponible');
        
        // $this->addFlash('info','elle sera bientôt mise en place');
        $contactByMail = new ContactByMail();
        $form = $this->createForm(ContactByMailType::class,$contactByMail) ;
        return $this->render('advert/contact.html.twig', array('form'=>$form->createView()));
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
        if((int)$page < 1)
        {
            throw$this->createNotFoundException('Page "'.$page.'"inexistante');
        }
        //ici je fixe arbitrairement le nombre d'annonce par page à 3
        //mais bien sûr il faudrait utiliser un paramètre, et y accéder via $this->container->getParameter('nb_per_page')
        $nbPerPage = 3;
        //on récupère notre objet Pahginator
        $listAdverts = $repo->getAdverts($page,$nbPerPage); 
        //on calcule le nombre total de pages grâce au count($listAdverts)
        //qui retourne le nombre total d'annonces
        $nbPages = ceil(count($listAdverts)/$nbPerPage);
        //si la page n'existe pas, on retourne une 404
        if($page > $nbPages)
        {
          throw $this->createNotFoundException('Page "'.$page.'"inexistante');
        }
        //on donne toutes les infos nécessaires à la vue
        return $this->render('advert/indexAdvert.html.twig', array(
            'listAdverts' => $listAdverts,
            'nbPages'     =>$nbPages,
            'page'        =>$page
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
    public function add(Request $request, AntispamService $antispam, EntityManagerInterface $em) :Response
    {
            $advert = new Advert;

            $form = $this->createForm (AdvertType::class, $advert);
            
            if($request->isMethod('POST'))
            {
              //*****on fait le lien requête <=> formulaire***\\
              //*****à partir de maintenant, la variable $advert contient les valeurs entrées dans le formulaire par le visiteur***\\
              $form->handleRequest($request);
              //*****test de validité des données contenues dans le formulaire***\\
              if($form->isValid())
              {
                if($antispam->isSpam($advert->getContent()) )
                {
                  throw new NotFoundHttpException('le text descriptif doit contenir au moins 50 caractères');
                }
                // c'est elle qui déplace l'image là où on veut les stocker
                // $advert->getImage()->upload();

                $em->persist($advert);
                $em->flush();
                $this->addFlash('notice', 'Annonce bien enregistrée.');
                return $this->redirectToRoute('advert_view', array('id' =>$advert->getId()));
              }
            }
            return $this->render('advert/addAdvert.html.twig', array('form'=>$form->createView()));
    }
    // /*********************edit********************************************/ //
    public function edit($id, Request $request,AntispamService $antispam,ImageRepository $repoImg ,AdvertRepository $repo,CategoryRepository $repoCat,EntityManagerInterface $em) :Response
    {
        $advert = new Advert();
        $advert = $repo->findOneBy(['id'=>$id]);
        //*****hydrater$form avec le résultat de  l'entité pour id recherché ($advert)  
        $form = $this->createForm(AdvertEditType::class,$advert);//***externalisation du formulaire */
          if($request->isMethod('POST'))
          {
            //*****hydrater $form avec les datas contenues dans la requete POST grance à la métode handleRequest() 
            if($form->handleRequest($request)->isValid())
            {
              if($antispam->isSpam($advert->getContent()) )
              {
                throw new NotFoundHttpException('le text descriptif doit contenir au moins 50 caractères');
              }
              $em->persist($advert);//persiste en cascade avec l'annonce
              $em->flush();
              $this->addFlash('notice', 'modification(s) bien enregistrée(s).');
              return $this->redirectToRoute('advert_view', array('id' =>$advert->getId()));
            } 
          }
        return $this->render('advert/editAdvert.html.twig',array(
            'advert' => $advert, 'form'=>$form->createView()
          ));
    }
    public function delete($id, AdvertRepository $repo,Request $request,EntityManagerInterface $em) : Response //Optimisé=Okay
    {
        $advert = new Advert();
        $advert=$repo->find($id);
        if($advert === null)
        {
          throw new NotFoundHttpException('\'annonce avec l\'id : '.$id .' n\'existe pas');
        }

        // On crée un formulaire vide, qui ne contiendra que le champ CSRF
        // Cela permet de protéger la suppression d'annonce contre cette faille
        $form = $this->createFormBuilder()->getForm();
        
        if ($request->isMethod('POST') && $form->handleRequest($request)->isValid()) {
          $em->remove($advert);
          $em->flush();
          //******************au cas où on voulais supprimer les catégories ratachées à l'annonce */
          // foreach($advert->getCategories() as $category)
          // {
          //   $advert->removeCategory($category);
          // }
          $this->addFlash('info', "L'annonce a bien été supprimée.");
    
          return $this->redirectToRoute('advert_index');
        }
        
        return $this->render('advert/deleteAdvert.html.twig', array(
          'advert' => $advert,
          'form'   => $form->createView(),
        ));
        dd($form);
          
      }
}
