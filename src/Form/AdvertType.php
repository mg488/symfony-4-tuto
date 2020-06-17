<?php

namespace App\Form;

use DateTime;
use App\Entity\Advert;
use App\Form\ImageType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;

class AdvertType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('title', TextType::class)
            ->add('author', TextType::class)
            ->add('date_crea', DateType::class,['label'=>'Date de création',
                      'label_attr' => ['class' => 'col-sm-3'],
                      'attr' => ['class' => 'col-sm-9']
                      ])
            ->add('published', CheckboxType::class , array('required'=>false,
                      'attr' => ['class' => 'col-sm-3']))
                      ->add('save',      SubmitType::class)
            ->add('content', TextareaType::class)
            ->add('image', ImageType::class)
            ->add('save', SubmitType::class, ['attr'=>['class'=>'col-sm-6']])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Advert::class,
        ]);
    }
}
