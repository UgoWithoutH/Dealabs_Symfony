<?php

namespace App\Controller;

use App\Entity\Alert;
use App\Entity\User;
use App\Form\AlertFormType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AlertController extends AbstractController
{
    #[Route('/alert', name: 'app_alert')]
    public function getAlert(EntityManagerInterface $entityManager): Response
    {
        $user = $this->getUser();
        $alerts = null;
        if ($user instanceof User) {
            $alerts = $entityManager->getRepository(Alert::class)->findByUser($user);
        }

        return $this->render('alert/alerts.html.twig', [
            'alerts' => $alerts,
        ]);
    }

    #[Route('/alert/add', name: 'app_alert_add')]
    public function addAlert(Request $request, EntityManagerInterface $entityManager): Response
    {
        $alert = new Alert();
        $form = $this->createForm(AlertFormType::class, $alert);
        $form->handleRequest($request);
        $alerts = null;
        $user = $this->getUser();
        if ($user instanceof User) {
            $alerts = $alerts = $entityManager->getRepository(Alert::class)->findByUser($user);
        }

        if ($form->isSubmitted() && $form->isValid()) {
            if ($user instanceof User) {
                $alert->setUser($user);
            }
            $entityManager->persist($alert);
            $entityManager->flush();

            return $this->redirectToRoute('app_alert_add');
        }

        return $this->render('alert/add/addAlert.html.twig', [
            'form' => $form->createView(),
            'alerts' => $alerts,
        ]);
    }
}
