<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\UserType;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityNotFoundException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class AdminController extends AbstractController
{
    
       ##**************ajouter admins
       public function createAdmin(Request $request, EntityManagerInterface $em, UserRepository $userRespository)
       {
         $user = new User();
         $form = $this->createForm(UserType::class,$user);
   
         if($request->isMethod('POST'))
         {
             
           if($form->handleRequest($request)->isValid())
           {
            try {
                $user->setSalt('');
                $em->persist($user);
                $em->flush();
                $listUsers = $userRespository->findAll();
                $this->addFlash('notice', 'user bien enregistrée.');
                return $this->redirectToRoute('admin_list',array('listUsers'=>$listUsers));
                }
            catch(\Doctrine\DBAL\Exception\UniqueConstraintViolationException $e) 
                {
                    throw new \Symfony\Component\HttpKernel\Exception\HttpException(409, "username existe déjà" );
                } 
                catch(\Doctrine\DBAL\Exception\ConstraintViolationException $e ) 
                {
                    throw new \Symfony\Component\HttpKernel\Exception\HttpException(409, "Bad request on Transaction" );
                } 
                catch(\Doctrine\DBAL\Exception\TableNotFoundException $e ) 
                {
                    throw new \Symfony\Component\HttpKernel\Exception\HttpException(409, "Table User pas trouvée !" );
                }
           }
         }
         return $this->render('admin/createAdmin.html.twig',array('form'=>$form->createView()));
       }
       ##**liste de amdmins******************** */
       public function listAdmin(UserRepository $userRespository)
       {
           $user = new User();
           $listUsers = $userRespository->findAll();
           return $this->render('admin/listAdmin.html.twig',array('listUsers'=>$listUsers));
       }
       #***************Supprimer User*********
       public function deleteAdmin($id,UserRepository $userRespository, Request $request, EntityManagerInterface $em)
       {
           $user = new User();
           $user = $userRespository->find($id);
        //    dd($request);
           if($user === null)
           {
             throw new NotFoundHttpException('\'utilisateur avec l\'id : '.$id .' n\'existe pas');
           }
           if($request->isMethod('GET'))
           {
                $em->remove($user) ;
                $em->flush();
                $listUsers = $userRespository->findAll();
                return $this->render('admin/listAdmin.html.twig',array('listUsers'=>$listUsers)); 
            }
            $listUsers = $userRespository->findAll();
            return $this->redirectToRoute('admin_list',array('listUsers'=>$listUsers));
       }
}
