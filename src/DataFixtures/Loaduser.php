<?php

namespace App\DataFixtures;

use App\Entity\UserOld;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;

class Loaduser extends Fixture
{
    public function load(ObjectManager $manager)
    {
          // Les noms d'utilisateurs à créer
        // $listNames = array('Moussa','Alexandre', 'Ibrahima','Marine', 'Anna','Djiby');

        // foreach ($listNames as $name) 
        // {
        //     // On crée l'utilisateur
        //     $user = new User;
        //     // Le nom d'utilisateur et le mot de passe sont identiques pour l'instant
        //     $user->setUsername($name);
        //     $user->setPassword($name);

        //     // On ne se sert pas du sel pour l'instant
        //     $user->setSalt('');
        //     // On définit uniquement le role ROLE_USER qui est le role de base
        //     $user->setRoles(array('ROLE_USER'));

        //     // On le persiste
        //     $manager->persist($user);
        // }
        $user = new UserOld;
        $user->setUsername('guinz');
        $user->setPassword('guinzpass');
        $user->setSalt('');
        $user->setRoles(array('ROLE_ADMIN'));
        $manager->persist($user);
        // $manager->flush();
    }
}
