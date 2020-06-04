<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class AdvertController extends AbstractController
{
    
    public function index()
    {
        return $this->render('advert/indexAdvert.html.twig', [
            'controller_name' => 'AdvertController',
        ]);
    }
}
