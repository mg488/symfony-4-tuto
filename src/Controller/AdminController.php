<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\UserType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class AdminController extends AbstractController
{
    
       ##pour la gestion de mon formulaire
       public function indexAdmin(Request $request )
       {
         $user = new User();
         $form = $this->createForm(UserType::class,$user);
   
         if($request->isMethod('POST'))
         {
           if($form->handleRequest($request)->isValid())
           {
               dd($form);
             //notre enregistrement
           }
         }
         return $this->render('admin/indexAdmin.html.twig',array('form'=>$form->createView()));
       }
}
