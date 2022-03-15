<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\RegisterType;
use Doctrine\Persistence\ManagerRegistry;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;

class RegisterController extends AbstractController
{
    private $entityManager;
    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager; //permet d'utiliser entityManager sur toute les fonctions
    }

    #[Route('/inscription', name: 'app_register')]
    public function index(Request $request, UserPasswordHasherInterface $hasher): Response
    {
        $user = new User(); //création l'objet user 
        $form = $this->createForm( RegisterType::class, $user ); // création du formulaire 
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid())
        {
            $user = $form->getData(); //on récupère les données du formulaire

            $password = $hasher->hashPassword($user, $user->getPassword()); //on récupère le mdp pour le crypter
            $user->setPassword($password);

            $this->entityManager->persist($user);
            $this->entityManager->flush();
            
        }

        return $this->render('register/index.html.twig', [
            'form' => $form->createView(), //on envoi le formulaire à la view
        ]);
    }
}
