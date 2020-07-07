<?php

namespace App\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class MainController extends AbstractController
{
    #pour la gestion de la langue
    public function changeLocale($locale, Request $request)
    {
        // dd($locales);
        // if(strpos($request->headers->get('accept-language'), $locale) == false)
        // {
        //     throw new NotFoundHttpException("La langue ".$locale." n'existe pas ! ");
        // }
        // dd($request->headers->get('accept-language'));
        $request->getSession()->set('_locale',$locale);

        if($request->headers->get('referer') != null) 
        {
            return $this->redirect($request->headers->get('referer')); #rester dans la page qui demande un changement de langue
        }
    }
}
