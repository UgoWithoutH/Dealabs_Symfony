<?php

namespace App\Utility;

use App\Entity\Badge;
use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;

final class BadgesChecker
{
    private const SURVEILLANT_TITLE = 'Surveillant';
    private const COBAYE_TITLE = 'Cobaye';
    private const RAPPORT_DE_STAGE_TITLE = 'Rapport de stage';

    public static function checkSurveillant(User $user, EntityManagerInterface $entityManager)
    {
        if ($user->getNumberOfVotes() >= 10) {
            $badge = $entityManager->getRepository(Badge::class)->findByTitle(self::SURVEILLANT_TITLE);
            $user->addBadge($badge);
            $entityManager->persist($user);
            $entityManager->flush();
        }
    }

    public static function checkCobaye(User $user, EntityManagerInterface $entityManager)
    {
        if (($user->getDeals()->count() + $user->getPromoCodes()->count()) >= 10) {
            $badge = $entityManager->getRepository(Badge::class)->findByTitle(self::COBAYE_TITLE);
            $user->addBadge($badge);
            $entityManager->persist($user);
            $entityManager->flush();
        }
    }

    public static function checkRapportDeStage(User $user, EntityManagerInterface $entityManager)
    {
        if ($user->getComments()->count() >= 10) {
            $badge = $entityManager->getRepository(Badge::class)->findByTitle(self::RAPPORT_DE_STAGE_TITLE);
            $user->addBadge($badge);
            $entityManager->persist($user);
            $entityManager->flush();
        }
    }
}
