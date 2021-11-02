<?php

namespace App\Controller\user;

use App\Form\EditPasswordType;
use App\Form\EditProfileType;
use App\Form\PassWordEditType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;



class UsersController extends AbstractController
{
    #[Route('/users', name: 'users')]
    public function index(): Response
    {
        return $this->render('users/index.html.twig', [
            'controller_name' => 'UsersController',
        ]);
    }
    //moddification du profile 
    #[Route('/users/profile/edit', name: 'users_profile_edit')]
    public function edit(Request $request)
    {
        $user = $this->getUser(); //récup les info de l'utilisateur
        $form = $this->createForm(EditProfileType::class, $user); 
        
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){
            $em = $this->getDoctrine()->getManager();
            $em->persist($user);
            $em->flush();

    $this->addFlash('message', 'Profile mis à jour');
        return $this->redirectToRoute('users');
        }

        return $this->render('users/edit.html.twig', [
            'form' => $form->createView()
        ]);
    }


    //modification du mot de passe
    #[Route('/users/password/edit', name: 'users_password_edit')]
    public function editPassWord(Request $request)
    {   

        $user = $this->getUser(); //récup les info de l'utilisateur
        $form = $this->createForm(EditPasswordType::class, $user); 
        
        $form->handleRequest($request);
         //vérifie si les mdp son identique 
        if( $request->request->get('password') == $request->request->get('checkPassword')){
           
        if($form->isSubmitted() && $form->isValid()){
            $em = $this->getDoctrine()->getManager();
            $em->persist($user);
            $em->flush();

    $this->addFlash('afficher', 'mots de passe modifier');
        return $this->redirectToRoute('users');
        }
    }
        else{
            $this->addFlash("error", "le mot de passe n'est pas identique");
        }
    
        return $this->render('users/passwordEdit.html.twig', [
            'form' => $form->createView()
        ]);
    }

}
