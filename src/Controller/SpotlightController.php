<?php

namespace App\Controller;

use App\Dto\DealPromoCodeDTO;
use App\Entity\Deal;
use App\Entity\PromoCode;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class SpotlightController extends AbstractController
{
    #[Route('/spotlight', name: 'app_spotlight')]
    // ...

    public function getSpotlight(EntityManagerInterface $entityManager): Response
    {
        $deals = $entityManager->getRepository(Deal::class)->findAll();
        $promocodes = $entityManager->getRepository(PromoCode::class)->findAll();

        $combinedCollection = array_merge($deals, $promocodes);

        $dtoList = [];
        foreach ($combinedCollection as $item) {
            $dto = new DealPromoCodeDTO();

            if ($item instanceof Deal) {
                $dto->setDeal($item);
            } elseif ($item instanceof PromoCode) {
                $dto->setPromoCode($item);
            }

            $dtoList[] = $dto;
        }

        usort($dtoList, function ($a, $b) {
            return count($b->getComments()) - count($a->getComments());
        });

        $currentDate = new \DateTime();
        $filteredDeals = array_filter($dtoList, function ($item) use ($currentDate) {
            $weekAgo = $currentDate->sub(new \DateInterval('P7D'));

            return $item->getPublicationDatetime() > $weekAgo;
        });

        return $this->render('spotlight/spotlight.html.twig', [
            'controller_name' => 'SpotlightController',
            'filteredDeals' => $filteredDeals,
        ]);
    }

    #[Route('/spotlight/hot', name: 'app_spotlight_hot')]
    public function hotDeals(EntityManagerInterface $entityManager): Response
    {
        $deals = $entityManager->getRepository(Deal::class)->findHotDeals();
        $promoCodes = $entityManager->getRepository(PromoCode::class)->findHotPromoCodes();

        $combinedCollection = array_merge($deals, $promoCodes);

        usort($combinedCollection, function ($a, $b) {
            return $b->getPublicationDatetime() <=> $a->getPublicationDatetime();
        });

        return $this->render('spotlight/hot/spotlightHot.html.twig', [
            'filteredDeals' => $combinedCollection,
        ]);
    }
}
