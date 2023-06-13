<?php

namespace App\Controller;

use App\Dto\DealPromoCodeDTO;
use App\Entity\Deal;
use App\Entity\PromoCode;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class SearchController extends AbstractController
{
    #[Route('/search', name: 'app_search')]
    public function search(Request $request, EntityManagerInterface $entityManager): Response
    {
        $searchTerm = $request->query->get('searchTerm');

        $deals = $entityManager->getRepository(Deal::class)->findAll();
        $promocodes = $entityManager->getRepository(PromoCode::class)->findAll();
        $filteredDtos = [];

        foreach ($deals as $deal) {
            if (str_contains($deal->getTitle(), $searchTerm) || str_contains($deal->getDescription(), $searchTerm)) {
                $dto = new DealPromoCodeDTO();
                $dto->setDeal($deal);
                $filteredDtos[] = $dto;
            }
        }

        foreach ($promocodes as $promocode) {
            if (str_contains($promocode->getTitle(), $searchTerm) || str_contains($promocode->getDescription(), $searchTerm)) {
                $dto = new DealPromoCodeDTO();
                $dto->setPromoCode($deal);
                $filteredDtos[] = $dto;
            }
        }

        return $this->render('search/alerts.html.twig', [
            'searchTerm' => $searchTerm,
            'filteredDtos' => $filteredDtos,
        ]);
    }
}
