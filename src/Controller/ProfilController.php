<?php

namespace App\Controller;

use App\Dto\DealPromoCodeDTO;
use App\Entity\Deal;
use App\Entity\PromoCode;
use App\Entity\User;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ProfilController extends AbstractController
{
    #[Route('/profil/save', name: 'app_profil_save')]
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

        return $this->render('profil/save.html.twig', [
            'dealsDtoSave' => $dtoList,
        ]);
    }
}
