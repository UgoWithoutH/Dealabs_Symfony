<?php

namespace App\Utility;

use App\Entity\Alert;
use App\Entity\Deal;
use Doctrine\ORM\EntityManagerInterface;

final class AlertsChecker
{
    public static function checkAlerts($dealOrPromoCode, EntityManagerInterface $entityManager)
    {
        $alerts = $entityManager->getRepository(Alert::class)->findAll();
        foreach ($alerts as $alert) {
            $keyword = $alert->getKeyword();
            $minimumTemperature = $alert->getMinimumTemperature();

            if ((str_contains($dealOrPromoCode->getTitle(), $keyword)
                || str_contains($dealOrPromoCode->getDescription(), $keyword))
                && $minimumTemperature <= $dealOrPromoCode->getHotLevel()) {
                if ($dealOrPromoCode instanceof Deal) {
                    $alert->addDeal($dealOrPromoCode);
                } else {
                    $alert->addPromoCode($dealOrPromoCode);
                }
                $entityManager->persist($alert);
                $entityManager->flush();
            }
        }
    }
}
