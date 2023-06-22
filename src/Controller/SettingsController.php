<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\ParamsType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\FormType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;

class SettingsController extends AbstractController
{
    #[Route('/settings', name: 'app_settings_profile')]
    public function index(Request $request, EntityManagerInterface $entityManager): Response
    {
        $user = $this->getUser();

        $form = $this->createForm(ParamsType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_settings_profile');
        }

        return $this->render('settings/profile/profile.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    #[Route('/settings/password', name: 'app_change_password')]
    public function changePassword(Request $request, EntityManagerInterface $entityManager, UserPasswordHasherInterface $userPasswordHasher): Response
    {
        $form = $this->createForm(FormType::class)
            ->add('actualPassword', PasswordType::class)
            ->add('newPassword', PasswordType::class);
        $user = $this->getUser();
        $form->handleRequest($request);
        $errors = [];
        if ($form->isSubmitted() && $form->isValid() && $user instanceof User) {
            $data = $form->getData();
            $actualPassword = $data['actualPassword'];

            if ($userPasswordHasher->isPasswordValid($user, $actualPassword)) {
                $newPassword = $data['newPassword'];
                $user->setPassword($userPasswordHasher->hashPassword(
                    $user,
                    $newPassword
                ));

                $entityManager->flush();

                return $this->redirectToRoute('app_settings_profile');
            } else {
                $errors[] = 'le mot de passe actuel est incorrect';
            }
        }

        return $this->render('settings/profile/password/password.html.twig', [
            'form' => $form->createView(),
            'errors' => $errors,
        ]);
    }
}
