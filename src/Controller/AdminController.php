<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class AdminController extends AbstractController
{
    
    public function indexAdmin()
    {
        return $this->render('admin/indexAdmin.html.twig', [
            'controller_name' => 'AdminController',
        ]);
    }
}
