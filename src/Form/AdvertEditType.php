<?php

namespace App\Form;

use App\Entity\Advert;
use App\Repository\CategoryRepository;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class AdvertEditType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->remove('date_crea');//on ne veut pas afficher la date de création de l'annonce lors de la modification
    }

    public function getParent()
    {
        return AdvertType::class;
    }
}
