<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class AdvertController extends AbstractController
{
    
    public function index()
    {
        return $this->render('advert/indexAdvert.html.twig', [
            'name' => 'Mnd variable passee en parametre depuis le controlleur',
        ]);
    }
    public function view($id)
    {
        return $this->render('advert/viewAdvert.html.twig',['id'=>$id]);
    }

    public function add()
    {
        return $this->render('advert/addAdvert.html.twig');
    }
    public function edit($id)
    {
        return $this->render('advert/editAdvert.html.twig');
    }
    public function delete($id)
    {
        return $this->render('advert/deleteAdvert.html.twig');
    }
}
