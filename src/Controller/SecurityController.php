<?php

namespace App\Controller;

use App\Form\ChangePasswordFormType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class SecurityController extends AbstractController
{
    /**
     * @Route("/login", name="app_login")
     */
    public function login(AuthenticationUtils $authenticationUtils): Response
    {
        // if ($this->getUser()) {
        //     return $this->redirectToRoute('target_path');
        // }

        // get the login error if there is one
        $error = $authenticationUtils->getLastAuthenticationError();
        // last username entered by the user
        $lastUsername = $authenticationUtils->getLastUsername();

        return $this->render('security/login.html.twig', ['last_username' => $lastUsername, 'error' => $error]);
    }

    /**
     * @Route("/logout", name="app_logout")
     */
    public function logout(Request $request)
    {
        throw new \LogicException('This method can be blank - it will be intercepted by the logout key on your firewall.');
    }

    /**
     * @Route("/redefinepassword", name="redefinepassword")
     */
    public function resetPassword(Request $request, UserPasswordEncoderInterface $passwordEncoder, EntityManagerInterface $entityManager)
    {
        $employe = $this->getUser();
        $form = $this->createForm(ChangePasswordFormType::class, $employe);  

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {    

            $employe->setPassword(                             
                $passwordEncoder->encodePassword(        
                    $employe,
                    $form->get('plainPassword')->getData()
                )
             );
            $employe->setRedefinePassword(true);          
            $entityManager->flush();             
            
            return $this->redirectToRoute('employe');  
                    
        }

        return $this->render('security/redefinepassword.html.twig', [    
            'registrationForm' => $form->createView(),                  
        ]);
    }
    }





