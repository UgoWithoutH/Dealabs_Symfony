<?php

namespace App\Service;

class TimeElapsedService
{
    public function calculateTimeElapsed(\DateTimeInterface $dateTime)
    {
        $now = new \DateTime();
        $interval = $now->diff($dateTime);

        $elapsed = '';
        if ($interval->y > 0) {
            $elapsed = $interval->y.' an(s)';
        } elseif ($interval->m > 0) {
            $elapsed = $interval->m.' mois';
        } elseif ($interval->d > 0) {
            $elapsed = $interval->d.' jour(s)';
        } elseif ($interval->h > 0) {
            $elapsed = $interval->h.' heure(s)';
        } elseif ($interval->i > 0) {
            $elapsed = $interval->i.' minute(s)';
        } elseif ($interval->s > 0) {
            $elapsed = $interval->s.' seconde(s)';
        }

        return $elapsed;
    }
}
