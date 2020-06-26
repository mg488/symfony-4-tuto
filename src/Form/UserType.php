<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;

class UserType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        // $dataTransformer = new DataTransformer();
        $builder
            ->add('username', TextType::class)
            ->add('password',PasswordType::class)
            ->add('roles', ChoiceType::class,[
                'expanded' =>false,
                'multiple' => true,
                'choices'  => [
                    'user'        => 'ROLE_USER',
                    'auteur'      => 'ROLE_AUTEUR',
                    'modérateur'  => 'ROLE_MODERATEUR',
                    'admin'       => 'ROLE_ADMIN',
                    'Super admin' => 'ROLE_SUPER_ADMIN']
             ])
            ->add('save', SubmitType::class, ['label'=>'Enregistrer',
            'attr'=>['class'=>'col-sm-3 col-sm-offset-6 btn btn-success']])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
