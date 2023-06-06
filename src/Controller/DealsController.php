<?php

namespace App\Controller;

use App\Entity\Deal;
use App\Entity\User;
use App\Form\DealFormType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class DealsController extends AbstractController
{
    #[Route('/deals', name: 'app_deals')]
    public function getDeals(EntityManagerInterface $entityManager): Response
    {
        $deals = $entityManager->getRepository(Deal::class)->findAll();

        return $this->render('deals/deals.html.twig', [
            'controller_name' => 'DealsController',
            'deals' => $deals,
        ]);
    }

    #[Route('/deals/add', name: 'app_add_deal')]
    public function addDeal(Request $request, EntityManagerInterface $entityManager): Response
    {
        $deal = new Deal();
        $form = $this->createForm(DealFormType::class, $deal);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $deal->setPublicationDatetime(new \DateTime());
            $user = $this->getUser();

            if ($user instanceof User) {
                $deal->setAuthor($user);
            }
            $entityManager->persist($deal);
            $entityManager->flush();

            return $this->redirectToRoute('app_deals');
        }

        return $this->render('deals/add/addDeal.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}
