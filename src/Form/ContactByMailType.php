<?php

namespace App\Form;

use App\Entity\ContactByMail;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class ContactByMailType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('firstName')
            ->add('lastName')
            ->add('content')
            ->add('email')
            // ->add('save', SubmitType::class, ['label'=>'Enregistrer',
            // 'attr'=>['class'=>'col-sm-2 btn btn-success pull-right']])
            ->add('save', SubmitType::class,['label'=>'Enregistrer',
            'attr'=>['class'=>'col-sm-2 btn btn-success pull-right']])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => ContactByMail::class,
        ]);
    }
}
