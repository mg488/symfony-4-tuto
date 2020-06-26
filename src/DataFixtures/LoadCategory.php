<?php

namespace App\DataFixtures;

use App\Entity\Category;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;

class LoadCategory extends Fixture
{
    public function load(ObjectManager $manager)
    {
        // $product = new Product();
        //liste des noms de catégorie à ajouter
        $names = array(
                'Développement Web',
                'Développement mobile',
                'Graphisme',
                'Intégration',
                'Réseau'
            );
        foreach($names as $name){
            $category = new Category();
            $category->setName($name);
            $manager->persist($category);
        }
    
        // $manager->flush();
    }
}
