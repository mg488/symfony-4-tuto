<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\UserType;
use App\Form\UserEditType;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityNotFoundException;
use Symfony\Component\HttpFoundation\Request;
use FOS\UserBundle\Model\UserManagerInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class AdminUsersController extends AbstractController
{
       ##**liste de users******************** */
       public function listOfUsers(UserManagerInterface $userManager)
       {
          $listUsers = $userManager->findUsers();
          return $this->render('admin/listUsers.html.twig',array('listUsers'=>$listUsers));
       }
       ##**************ajouter user gérer avec FOSUserBundles
       #Roles users
       public function rolesOfUser($id,Request $request, UserManagerInterface $userManager)
       {
          $user = new User;   
          $user = $userManager->findUserBy(array('id' => $id));
         
          $formBuilder = $this->createFormBuilder($user);
          
          $formBuilder ->add('username', TextType::class,['disabled'=>true])
                        ->add('save',  SubmitType::class)
                        ->add('roles', ChoiceType::class,[
                          'expanded' =>false,
                          'multiple' => true,
                          'choices'  => [
                              'user'        => 'ROLE_USER',
                              'auteur'      => 'ROLE_AUTEUR',
                              'modérateur'  => 'ROLE_MODERATEUR',
                              'admin'       => 'ROLE_ADMIN',
                              'Super admin' => 'ROLE_SUPER_ADMIN']
                       ]);
          $form = $formBuilder->getForm();
          if($user === null)
           {
             throw new NotFoundHttpException('\'utilisateur avec l\'id : '.$id .' n\'existe pas');
           }
           if($request->isMethod('POST') )
           {
              if($form->handleRequest($request)->isValid() )
              {
                $userManager->updateUser($user);

                $this->addFlash('notice','Roles modifiés avec succès !');

                $listUsers = $userManager->findUsers();

                return $this->redirectToRoute('admin_list_users',array('listUsers'=>$listUsers));
                
              }
           }
           
           return $this->render('admin/editUsers.html.twig',array('user'=>$user,'form'=>$form->createView()));
       }

       #Supprimer User*********
       public function deleteUser($id, UserManagerInterface $userManager, Request $request)
       {
           $user = new User();
           $user = $userManager->findUserBy(array('id' => $id));

           $formBuilder = $this->createFormBuilder($user);
           $form = $formBuilder->getForm();
           if($user === null)
           {
             throw new NotFoundHttpException('\'utilisateur avec l\'id : '.$id .' n\'existe pas');
           }
           
           if($request->isMethod('POST'))
           {
             
             if($form->handleRequest($request)->isSubmitted())
              // dd($user);
              // dd($form);
              $userManager->deleteUser($user) ;

              $this->addFlash('notice','utilisateur supprimé avec succès !');
            
              $listUsers = $userManager->findUsers();
    
              return $this->redirectToRoute('admin_list_users',array('listUsers'=>$listUsers));
            }
            return $this->render('admin/deleteUser.html.twig',array(
              'user'=>$user,
              'form'=>$form->createView()));
       }
      

}
