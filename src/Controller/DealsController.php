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

    #[Route('/deals/detail', name: 'app_deal_detail')]
    public function getDealDetail(Request $request, EntityManagerInterface $entityManager): Response
    {
        $dealId = $request->query->get('dealId');
        $deal = $entityManager->getRepository(Deal::class)->find($dealId);

        return $this->render('deals/detail/detail.html.twig', [
            'controller_name' => 'DealsController',
            'deal' => $deal,
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
            $deal->setHotLevel(0);
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

    #[Route('/deals/hotLevel/decrease', name: 'app_deals_decrease_hotlevel')]
    public function decreaseHotLevel(Request $request, EntityManagerInterface $entityManager): Response
    {
        $dealId = $request->query->get('dealId');
        $deal = $entityManager->getRepository(Deal::class)->find($dealId);

        $deal->setHotLevel($deal->getHotLevel() - 1);

        $entityManager->persist($deal);
        $entityManager->flush();

        return $this->redirectToRoute('app_deals');
    }

    #[Route('/deals/hotLevel/increase', name: 'app_deals_increase_hotlevel')]
    public function increaseHotLevel(Request $request, EntityManagerInterface $entityManager): Response
    {
        $dealId = $request->query->get('dealId');
        $deal = $entityManager->getRepository(Deal::class)->find($dealId);

        $deal->setHotLevel($deal->getHotLevel() + 1);

        $entityManager->persist($deal);
        $entityManager->flush();

        return $this->redirectToRoute('app_deals');
    }
}
