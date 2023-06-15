<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class SettingsController extends AbstractController
{
    #[Route('/settings', name: 'app_settings_profil')]
    public function index(): Response
    {
        return $this->render('settings/profil.html.twig', [
            'controller_name' => 'SettingsController',
        ]);
    }
}
