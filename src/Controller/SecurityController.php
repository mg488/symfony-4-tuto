<?php

namespace App\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class SecurityController extends AbstractController
{
  ##pour diriger les petits filous
    public function loginAction(Request $request,AuthenticationUtils $authenticationUtils)
    {
      // Si le visiteur est déjà identifié, on le redirige vers l'accueil
      if ($this->get('security.authorization_checker')->isGranted('IS_AUTHENTICATED_REMEMBERED')) 
      {
        return $this->redirectToRoute('advert_index');
        // return $this->render('advert/indexAdvert.html.twig');
      }
      // Le service authentication_utils permet de récupérer le nom d'utilisateur
      // et l'erreur dans le cas où le formulaire a déjà été soumis mais était invalide
      // (mauvais mot de passe par exemple)
      // remplacé par l'injection $authenticationUtils = $this->get('security.authentication_utils');
  
      return $this->render('advert/login.html.twig', array(
        'last_username' => $authenticationUtils->getLastUsername(),
        'error'         => $authenticationUtils->getLastAuthenticationError()
      ));
    }
 
}
