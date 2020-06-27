<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class UserController extends AbstractController
{
    
    public function createUser()
    {
        return $this->render('user/createUser.html.twig', [
            'controller_name' => 'UserController',
        ]);
    }
}
