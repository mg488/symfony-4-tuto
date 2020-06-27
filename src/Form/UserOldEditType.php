<?php

namespace App\Form;

use App\Form\UserOldType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class UserOldEditType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->remove('password');
    }

    public function getParent()
    {
        return UserOldType::class;
    }
}
