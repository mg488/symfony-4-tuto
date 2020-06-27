<?php

namespace App\Controller;

use App\Entity\UserOld;
use App\Form\UserOldType;
use App\Form\UserOldEditType;
use App\Repository\UserRepository;
use App\Repository\UserOldRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityNotFoundException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class AdminController extends AbstractController
{
    
       ##**************ajouter admins
       public function createAdmin(Request $request, EntityManagerInterface $em, UserOldRepository $userRespository)
       {
         $user = new UserOld();
         $form = $this->createForm(UserOldType::class,$user);
   
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
       public function listAdmin(UserOldRepository $userRespository)
       {
           $user = new UserOld();
           $listUsers = $userRespository->findAll();
           return $this->render('admin/listAdmin.html.twig',array('listUsers'=>$listUsers));
       }
       #***************Supprimer User*********
       public function deleteAdmin($id, UserOldRepository $userRespository, Request $request, EntityManagerInterface $em)
       {
           $user = new UserOld();
           $user = $userRespository->find($id);
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
       public function editAdmin($id, Request $request, UserOldRepository $userRespository, EntityManagerInterface $em)
       {
           $user = new UserOld();
           $user = $userRespository->find($id);
           if($user === null)
           {
             throw new NotFoundHttpException('\'utilisateur avec l\'id : '.$id .' n\'existe pas');
           }
           $form = $this->createForm(UserOldEditType::class,$user);
            if($request->isMethod('POST'))
            {
                if($form->handleRequest($request)->isValid())
                {
                    $em->persist($user);
                    $em->flush();
                    $this->addFlash('notice', 'modification(s) bien enregistrée(s).');
                    $listUsers = $userRespository->findAll();
                    return $this->render('admin/listAdmin.html.twig',array('listUsers'=>$listUsers));  
                }
            }
            return $this->render('admin/editAdmin.html.twig',array(
                'advert' => $user, 'form'=>$form->createView()
              ));
       }

}
