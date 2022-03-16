<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\ChangePasswordType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;

class AccountPasswordController extends AbstractController
{
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    #[Route('/compte/modifier-mon-mot-de-passe', name: 'app_account_password')]
    public function index(Request $request, UserPasswordHasherInterface $hasher): Response
    {
        $user = $this->getUser(); //on appel l'objet user
        $form = $this->createForm(ChangePasswordType::class, $user);

        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid())
        {
            $old_password = $form->get('old_password')->getData(); //on récupère le champ old_password et sa donné
            
            if($hasher->isPasswordValid($user, $old_password)) //est-ce que le mot de passe de l'objet user est identique à $old_password
            {
                $new_password = $form->get('new_password')->getData();
                $password = $hasher->hashPassword($user, $new_password); //on crypte le nouveau mdp
                $user->setPassword($password);

                $this->entityManager->persist($user);
                $this->entityManager->flush();

                $this->addFlash('success', "Votre mot de passe à bien été modifier");
            } 
            else
            {
                $this->addFlash('danger', "Votre mot de passe actuel n'est pas le bon");
            }
        }

        return $this->render('account/password.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}
