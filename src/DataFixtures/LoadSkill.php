<?php

namespace App\DataFixtures;

use App\Entity\Skill;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;

class LoadSkill extends Fixture
{
    public function load(ObjectManager $manager)
    {
        $names = array('PHP', 'Symfony' , 'C++' , 'Java' , 'Photoshop' , 'Blender' , 'Bloc-note');

        foreach($names as $name){
            $skill = new Skill();
            $skill->setName($name);
            $manager->persist($skill);
        }

        // $manager->flush();
    }
}
