<?php

namespace App\Controller;

use App\Entity\Utilisateur;
use App\Form\UtilisateurType;
use App\Repository\UtilisateurRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class UtilisateurController extends AbstractController
{
    #[Route('/utilisateur/connexion', name: 'utilisateur_connexion')]
    public function getConnexionForm(Request $request, UtilisateurRepository $utilisateurRepository): Response
    {
        $utilisateur = new Utilisateur();
        $form = $this->createForm(UtilisateurType::class, $utilisateur)
        ->add('submit', SubmitType::class);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $utilisateur = $form->getData();
            $utilisateur = $utilisateurRepository->findUserconnection($utilisateur);

            if (null != $utilisateur) {
                return $this->redirectToRoute('app_utilisateur');
            }
        }

        return $this->render('login.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    #[Route('/utilisateur/createAccount', name: 'utilisateur_createAccount')]
    public function getCreateAccountForm(Request $request, UtilisateurRepository $utilisateurRepository): Response
    {
        $utilisateur = new Utilisateur();
        $form = $this->createForm(UtilisateurType::class, $utilisateur)
            ->add('submit', SubmitType::class);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $utilisateur = $form->getData();
            $utilisateurRepository->save($utilisateur, true);

            return $this->redirectToRoute('app_utilisateur');
        }

        return $this->render('create_account.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}
