<?php

namespace App\Controller\Backend;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\User;
use Symfony\Component\HttpFoundation\Request;
use App\Form\EditUserFormType;
use App\Form\NewUserFormType;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class UserController extends AbstractController
{
    /**
     * @Route("/admin/users", name="backend_users")
     */
    public function index()
    {
        $users = $this->getDoctrine()
        ->getRepository(User::class)
        ->findAll();

        return $this->render('backend/user/index.html.twig', [
            'users' => $users,
        ]);
    }
    
    /**
     * @Route("/admin/user/create", name="add_user")
     */
     public function addUser(Request $request, UserPasswordEncoderInterface $encoder)
     {
         $user = new user();
 
         $form = $this->createForm(NewUserFormType::class, $user);
 
         $form->handleRequest($request);
 
         if ($form->isSubmitted() && $form->isValid()) {
             $user = $form->getData();
             $user->setCreatedAt(new \DateTime);
             $user->setRoles(["ROLE_USER"]);
             $user->setPassword($encoder->encodePassword($user, $user->getPassword()));

             $entityManager = $this->getDoctrine()->getManager();
             $entityManager->persist($user);
             $entityManager->flush();
 
             return $this->redirectToRoute('backend_users');
         }
         
 
         return $this->render('backend/user/create.html.twig', [
             'form' => $form->createView(),
         ]);
     }

    /**
     * @Route("/admin/users/{id}/edit", name="edit_user")
     */
    public function edit(User $user, $id, Request $request)
    {
        $form = $this->createForm(EditUserFormType::class, $user, array(
            'method' => 'PUT',
        ));

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $user = $form->getData();
            $user->setUpdatedAt(new \DateTime);

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($user);
            $entityManager->flush();

            return $this->redirectToRoute('backend_users');
        }

        
        return $this->render('backend/user/update.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}
