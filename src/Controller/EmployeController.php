<?php

namespace App\Controller;

use App\Entity\Employe;
use App\Form\RegistrationFormType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\String\Slugger\SluggerInterface;

class EmployeController extends AbstractController
{
    /**
     * @Route("/employe", name="employe")
     */
    public function index()
    {
        $employes = $this->getDoctrine()->getRepository(Employe::class)->findAll();

        return $this->render('employe/index.html.twig', [
            'employes' => $employes
        ]);
    }


    // *************** DELETE ***************

/**
* @Route("/employe/{id}/delete", name="employe_delete")
*/
public function delete($id){

    $employe = $this->getDoctrine()->getRepository(Employe::class)->find($id);

    $entityManager = $this->getDoctrine()->getManager();
    $entityManager->remove($employe); 
    $entityManager->flush();

    return $this->index();

}

// *************** AJOUT EN BDD ***************

    /**
     * @Route("/add/employe", name="add_employe")
     */
    public function addEmploye(Request $request, SluggerInterface $slugger, UserPasswordEncoderInterface $passwordEncoder) {
        
        $employe = new Employe();

        $form = $this->createForm(RegistrationFormType::class, $employe);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $employe->setPassword(
                $passwordEncoder->encodePassword(
                    $employe,
                    $form->get('plainPassword')->getData()
                )
            );

            $photo = $form->get('photo')->getData();

            if ($photo) {
                $originalFilename = pathinfo($photo->getClientOriginalName(), PATHINFO_FILENAME);
           
                $safeFilename = $slugger->slug($originalFilename);
                $newFilename = $safeFilename.'-'.uniqid().'.'.$photo->guessExtension();

                try {
                    $photo->move(
                        'images/',
                        $newFilename
                    );
                } catch (FileException $e) {
                    die(dump($e));
                }

                $employe->setPhoto($newFilename);
            }

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($employe);
            $entityManager->flush();

            return $this->redirectToRoute('employe');

        } 

            return $this->render('employe/add_employe.html.twig', [
                'form' => $form->createView(),
                'errors'=>$form->getErrors()

    
        ]);
    }
          


 

}




