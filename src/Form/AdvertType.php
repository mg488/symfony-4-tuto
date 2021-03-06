<?php

namespace App\Form;
use App\Entity\Advert;
use App\Form\ImageType;
use Symfony\Component\Form\FormEvent;
use App\Repository\CategoryRepository;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;

class AdvertType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $pattern='%';
        $builder
            ->add('title', TextType::class)
            ->add('author', TextType::class)
            ->add('date_crea', DateTimeType::class,['label'=>'Date de création',
                      'label_attr' => ['class' => 'col-sm-3'],
                      'attr' => ['class' => 'col-sm-9']
                      ])
            // ->add('published', CheckboxType::class , array('required'=>false,
            //           'attr' => ['class' => 'col-sm-3']))
                      ->add('save',      SubmitType::class)
            ->add('content', TextareaType::class)
            ->add('image', ImageType::class, array('required'=>false)) //******Image
            //*******1ere façon de faire (CollectionType): *********possibilité d'ajouter et supprimer catégorie (button)***********************************/
            // ->add('categories', CollectionType::class, array( //CollectionType =>liste de n'importe quoi
            //     'entry_type'   =>CategoryType::class,  // enty_type=> le type de liste pour remplir la CollectionType
            //     'label'=>'Catégories',
            //     'allow_add'    =>true,
            //     'attr' => ['class' => 'col-sm-12 col-sm-offset-2'],
            //     'allow_delete' =>true
            // ))//************************Category
            
            //*******2éme façon de faire (EntityType): ********liste des catégories, pas de possibilité d'ajouter de catégorie****************************************************************** */
            // ->add('categories', EntityType::class, array( //CollectionType =>liste de n'importe quoi
            //     'class'   => 'App\Entity\Category', //definit le type d'entité à selectionner 
            //     'choice_label'    =>'name', //comment afficher les entité dans le select du formulaire (on peut afficher nom et l'id à la fois etc)
            //     'attr' => ['class' => 'col-sm-9 col-sm-offset-3'],
            //     'multiple' =>true //pour pouvoir choisir 1 ou plusieurs catégories
            //     ))//************************Category
            //*******3éme façon de faire (query_builder): ******** ****************************************************************** */
            ->add('categories', EntityType::class, array( //CollectionType =>liste de n'importe quoi
                'class'   => 'App\Entity\Category', //definit le type d'entité à selectionner 
                'choice_label'    =>'name', //comment afficher les entité dans le select du formulaire (on peut afficher nom et l'id à la fois etc)
                'attr' => ['class' => 'col-sm-9 col-sm-offset-3'],
                'multiple' =>true, //pour pouvoir choisir 1 ou plusieurs catégories
                'query_builder'=> function(CategoryRepository $repository) use($pattern) {
                    return $repository->getLikeQueryBuilder($pattern);}
                ))//************************Category
            ->add('save', SubmitType::class, ['label'=>'Enregistrer',
            'attr'=>['class'=>'col-sm-2 btn btn-success pull-right']])
            ;
            /********Start **gestion des événements******************************************************************************** */
            // On ajoute une fonction qui va écouter un évènement : 
            //empêcher de dépublier une annonce une fois qu'elle est publiée.
            //1 - Si l'annonce n'est pas encore publiée, on peut modifier sa valeur de publication lorsqu'on modifie l'annonce;
            //2 - Si l'annonce est déjà publiée, on ne peut plus modifier sa valeur de publication lorsqu'on modifie l'annonce.
            $builder->addEventListener(
                FormEvents::PRE_SET_DATA,    // 1er argument : L'évènement qui nous intéresse : ici, PRE_SET_DATA
                function(FormEvent $event) { // 2e argument : La fonction à exécuter lorsque l'évènement est déclenché
                // On récupère notre objet Advert sous-jacent
                $advert = $event->getData();
                // Cette condition est importante
                if (null === $advert) {
                    return; // On sort de la fonction sans rien faire lorsque $advert vaut null
                }
                // Si l'annonce n'est pas publiée, ou si elle n'existe pas encore en base (id est null)
                if (!$advert->getPublished() || null === $advert->getId()) {
                    // Alors on ajoute le champ published
                    $event->getForm()->add('published', CheckboxType::class, array('required'=>false,
                            'attr' => ['class' => 'col-sm-3']));
                } 
                else 
                {
                    // Sinon, on le supprime
                    $event->getForm()->remove('published');
                }});
        }
        //**********End **gestion des événements************************************************************************************* */
        public function configureOptions(OptionsResolver $resolver)
        {
            $resolver->setDefaults([
                'data_class' => Advert::class,
            ]);
        }
}
