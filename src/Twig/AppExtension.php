<?php

namespace App\Twig;

use App\Service\TimeElapsedService;
use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;

class AppExtension extends AbstractExtension
{
    private $timeElapsedService;

    public function __construct(TimeElapsedService $timeElapsedService)
    {
        $this->timeElapsedService = $timeElapsedService;
    }

    public function getFilters()
    {
        return [
            new TwigFilter('time_elapsed', [$this, 'calculateTimeElapsed']),
        ];
    }

    public function calculateTimeElapsed(\DateTimeInterface $dateTime)
    {
        return $this->timeElapsedService->calculateTimeElapsed($dateTime);
    }
}