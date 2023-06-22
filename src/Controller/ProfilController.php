<?php

namespace App\Controller;

use App\Dto\DealPromoCodeDTO;
use App\Entity\User;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ProfilController extends AbstractController
{
    #[Route('/profile/insight', name: 'app_profil_insight')]
    public function insight(EntityManagerInterface $entityManager): Response
    {
        $user = $this->getUser();
        $postedCount = 0;
        $commentsCount = 0;
        $highestRatedItem = null;
        $averageRatingOneYear = 0;
        $percentageHot = 0;
        $badges = null;

        if ($user instanceof User) {
            $deals = $user->getDeals();
            $promocodes = $user->getPromoCodes();
            $postedCount = $deals->count() + $promocodes->count();
            $commentsCount = $user->getComments()->count();

            if ($deals->isEmpty()) {
                $highestRatedDeal = null;
            } else {
                $maxHotLevel = null;
                foreach ($deals as $deal) {
                    if ($deal->getHotLevel() > $maxHotLevel || null == $maxHotLevel) {
                        $maxHotLevel = $deal->getHotLevel();
                        $highestRatedDeal = $deal;
                    }
                }
            }

            if ($promocodes->isEmpty()) {
                $highestRatedPromoCode = null;
            } else {
                $maxHotLevel = null;
                foreach ($promocodes as $promocode) {
                    if ($promocode->getHotLevel() > $maxHotLevel || null == $maxHotLevel) {
                        $maxHotLevel = $deal->getHotLevel();
                        $highestRatedPromoCode = $deal;
                    }
                }
            }

            if (null !== $highestRatedPromoCode && null !== $highestRatedDeal) {
                $highestRatedItem = $highestRatedPromoCode->getHotLevel() >= $highestRatedDeal->getHotLevel()
                    ? $highestRatedPromoCode
                    : $highestRatedDeal;
            } elseif (null !== $highestRatedPromoCode) {
                $highestRatedItem = $highestRatedPromoCode;
            } elseif (null !== $highestRatedDeal) {
                $highestRatedItem = $highestRatedDeal;
            }

            $oneYearAgo = new \DateTime();
            $oneYearAgo->modify('-1 year');
            $filteredItems = new ArrayCollection([...$deals->toArray(), ...$promocodes->toArray()]);
            $filteredItemsOneYear = $filteredItems->filter(function ($item) use ($oneYearAgo) {
                return $item->getPublicationDatetime() >= $oneYearAgo;
            });

            $totalRating = 0;
            $totalItemsOneYear = $filteredItemsOneYear->count();
            foreach ($filteredItems as $item) {
                $totalRating += $item->getHotLevel();
            }
            if ($totalItemsOneYear > 0) {
                $averageRatingOneYear = $totalRating / $totalItemsOneYear;
            }

            $totalItems = $filteredItems->count();
            $hotItems = $filteredItems->filter(function ($item) {
                return $item->getHotLevel() > 100;
            });

            if ($totalItems > 0) {
                $percentageHot = ($hotItems->count() / $totalItems) * 100;
            }

            $badges = $user->getBadges();
        }

        return $this->render('profile/insight.html.twig', [
            'postedCount' => $postedCount,
            'commentsCount' => $commentsCount,
            'highestRatedItem' => $highestRatedItem,
            'averageRatingOneYear' => $averageRatingOneYear,
            'percentageHot' => $percentageHot,
            'badges' => $badges,
        ]);
    }

    #[Route('/profile/postedDeals', name: 'app_profil_posted_deals')]
    public function postedDeals(): Response
    {
        $user = $this->getUser();
        $dtoList = [];

        if ($user instanceof User) {
            $deals = $user->getDeals();
            $promoCodes = $user->getPromoCodes();

            foreach ($deals as $item) {
                $dto = new DealPromoCodeDTO();
                $dto->setDeal($item);
                $dtoList[] = $dto;
            }
            foreach ($promoCodes as $item) {
                $dto = new DealPromoCodeDTO();
                $dto->setPromoCode($item);
                $dtoList[] = $dto;
            }
        }

        usort($dtoList, function ($a, $b) {
            return $b->getPublicationDatetime() <=> $a->getPublicationDatetime();
        });

        return $this->render('profile/deals.html.twig', [
            'dealsDto' => $dtoList,
        ]);
    }

    #[Route('/profile/save', name: 'app_profil_save')]
    public function save(): Response
    {
        $user = $this->getUser();
        $dtoList = [];

        if ($user instanceof User) {
            $deals = $user->getDealsSave();
            $promoCodes = $user->getPromoCodesSave();

            foreach ($deals as $item) {
                $dto = new DealPromoCodeDTO();
                $dto->setDeal($item);
                $dtoList[] = $dto;
            }
            foreach ($promoCodes as $item) {
                $dto = new DealPromoCodeDTO();
                $dto->setPromoCode($item);
                $dtoList[] = $dto;
            }
        }

        usort($dtoList, function ($a, $b) {
            return $b->getPublicationDatetime() <=> $a->getPublicationDatetime();
        });

        return $this->render('profile/save.html.twig', [
            'dealsDtoSave' => $dtoList,
        ]);
    }
}
