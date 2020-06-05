<?php

namespace App\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class AdvertController extends AbstractController
{
    public function menuAction()
    {
      // On fixe en dur une liste ici, bien entendu par la suite
      // on la récupérera depuis la BDD !
      $listAdverts = array(
        array('id' => 1, 'title' => 'Recherche développeur Symfony'),
        array('id' => 2, 'title' => 'Mission de webmaster'),
        array('id' => 3, 'title' => 'Offre de stage webdesigner')
      );
  
      return $this->render('advert/menu.html.twig', array(
        // Tout l'intérêt est ici : le contrôleur passe // les variables nécessaires au template menu.html.twig!
        'listAdverts' => $listAdverts
      ));
    }
    // /*********************index********************************************/ //
    public function index($page) : Response
    {
          // Notre liste d'annonce en dur
            $listAdverts = array(
                array(
                'title'   => 'Recherche développpeur Symfony',
                'id'      => 1,
                'author'  => 'Alexandre',
                'content' => 'Nous recherchons un développeur Symfony débutant sur Lyon. Blabla…',
                'date'    => new \Datetime()),
                array(
                'title'   => 'Mission de webmaster',
                'id'      => 2,
                'author'  => 'Hugo',
                'content' => 'Nous recherchons un webmaster capable de maintenir notre site internet. Blabla…',
                'date'    => new \Datetime()),
                array(
                'title'   => 'Offre de stage webdesigner',
                'id'      => 3,
                'author'  => 'Mathieu',
                'content' => 'Nous proposons un poste pour webdesigner. Blabla…',
                'date'    => new \Datetime())
            );
  
        if($page < 1){
            throw$this->createNotFoundException('Page "'.$page.'"inexistante');
        }
        return $this->render('advert/indexAdvert.html.twig', array(
            'listAdverts' => $listAdverts
          ));
    }
    // /*********************views********************************************/ //
    public function view($id) :Response
    {
        $advert = array(
            'title'   => 'Recherche développpeur Symfony 2',
            'id'      => $id,
            'author'  => 'Alexandre',
            'content' => 'Nous recherchons un développeur Symfony 2 débutant sur Lyon. Blabla…',
            'date'    => new \Datetime()
          );
        return $this->render('advert/viewAdvert.html.twig',array(
            'advert' => $advert
          ));
    }

    // /*********************add********************************************/ //
    public function add(Request $request) :Response
    {
            if($request->isMethod('POST')){
                $this->addFlash('notice', 'Annonce bien enregistrée');
                return $this->redirectToRoute('advert_view', ['id' => 5]);
            }
            
            return $this->render('advert/addAdvert.html.twig');
    }
    // /*********************edit********************************************/ //
    public function edit($id, Request $request) :Response
    {
        // if($request->isMethod('POST')){
        //     $this->addFlash('notice', 'Annonce bien modifiée');
        //     $this->redirectToRoute('advert_view', ['id'=>5]);
        // }
        $advert = array(
            'title'   => 'Recherche développpeur Symfony',
            'id'      => $id,
            'author'  => 'Alexandre',
            'content' => 'Nous recherchons un développeur Symfony débutant sur Lyon. Blabla…',
            'date'    => new \Datetime()
          );
        return $this->render('advert/editAdvert.html.twig',array(
            'advert' => $advert
          ));
    }
    public function delete($id) :Response
    {
        // if($request->isMethod('GET')){
        //     $this->addFlash('notice', 'Annonce bien supprimée'); 
        //     $this->redirectToRoute('advert_view', ['id'=>5]);
        // }
        return $this->render('advert/deleteAdvert.html.twig');
    }
}
