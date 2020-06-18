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
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;

class AdvertType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('title', TextType::class)
            ->add('author', TextType::class)
            ->add('date_crea', DateTimeType::class,['label'=>'Date de création',
                      'label_attr' => ['class' => 'col-sm-3'],
                      'attr' => ['class' => 'col-sm-9']
                      ])
            ->add('published', CheckboxType::class , array('required'=>false,
                      'attr' => ['class' => 'col-sm-3']))
                      ->add('save',      SubmitType::class)
            ->add('content', TextareaType::class)
            ->add('image', ImageType::class) //******Image
            ->add('categories', CollectionType::class, array( //CollectionType =>liste de n'importe quoi
                'entry_type'   =>CategoryType::class,  // enty_type=> le type de liste pour remplir la CollectionType
                'label'=>'Catégories',
                'allow_add'    =>true,
                'attr' => ['class' => 'col-sm-12 col-sm-offset-2'],
                'allow_delete' =>true
            ))//************************Category
            ->add('save', SubmitType::class, ['label'=>'Enregistrer',
            'attr'=>['class'=>'col-sm-2 btn btn-success pull-right']])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Advert::class,
        ]);
    }
}
