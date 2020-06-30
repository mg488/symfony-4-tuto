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
                        ->add('roles', ChoiceType::class, [
                          'choices' => [
                              'Yes' => true,
                              'No' => false,
                              'Maybe' => null,
                          ]]);
           $form = $formBuilder->getForm();
          if($user === null)
           {
             throw new NotFoundHttpException('\'utilisateur avec l\'id : '.$id .' n\'existe pas');
           }
           if($request->isMethod('POST') )
           {
              if($form->handleRequest($request)->isValid() )
              {
                dd($form);
              }
           }
           
           return $this->render('admin/editUsers.html.twig',array('user'=>$user,'form'=>$form->createView()));
       }

      //  #edit Admin
      //  public function editAdmin($id, Request $request, UserRepository $userRespository, EntityManagerInterface $em)
      //  {
      //      $user = new User();
      //      $user = $userRespository->find($id);
      //      if($user === null)
      //      {
      //        throw new NotFoundHttpException('\'utilisateur avec l\'id : '.$id .' n\'existe pas');
      //      }
      //      $form = $this->createForm(UserEditType::class,$user);
      //       if($request->isMethod('POST'))
      //       {
      //           if($form->handleRequest($request)->isValid())
      //           {
      //               $em->persist($user);
      //               $em->flush();
      //               $this->addFlash('notice', 'modification(s) bien enregistrée(s).');
      //               $listUsers = $userRespository->findAll();
      //               return $this->render('admin/listAdmin.html.twig',array('listUsers'=>$listUsers));  
      //           }
      //       }
      //       return $this->render('admin/editAdmin.html.twig',array(
      //           'advert' => $user, 'form'=>$form->createView()
      //         ));
      //  }
      //  #***************Supprimer User*********
      //  public function deleteAdmin($id, UserRepository $userRespository, Request $request, EntityManagerInterface $em)
      //  {
      //      $user = new User();
      //      $user = $userRespository->find($id);
      //      if($user === null)
      //      {
      //        throw new NotFoundHttpException('\'utilisateur avec l\'id : '.$id .' n\'existe pas');
      //      }
      //      if($request->isMethod('GET'))
      //      {
      //           $em->remove($user) ;
      //           $em->flush();
      //           $listUsers = $userRespository->findAll();
      //           return $this->render('admin/listAdmin.html.twig',array('listUsers'=>$listUsers)); 
      //       }
      //       $listUsers = $userRespository->findAll();
      //       return $this->redirectToRoute('admin_list',array('listUsers'=>$listUsers));
      //  }
      

}
