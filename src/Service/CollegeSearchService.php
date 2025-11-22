<?php

namespace App\Service;

use App\Repository\CollegeRepository;
use App\Service\AI\GeminiService;

class CollegeSearchService
{
    public function __construct(private CollegeRepository $repo, private GeminiService $ai) {}

    public function find(string $profession): string
    {
        if (empty(trim($profession))) {
            throw new \InvalidArgumentException('Profession cannot be empty');
        }

        $colleges = $this->repo->findAll();
        $collegeNames = array_map(fn($c) => $c->getName(), $colleges);

        $prompt = "Посоветуй колледжи и университеты для профессии '{$profession}' в Кыргызстане.\n"
                . "Список известных колледжей и университетов: " . json_encode($collegeNames, JSON_UNESCAPED_UNICODE) . "\n\n"
                . "Предоставь подробные рекомендации с объяснением, почему каждый колледж подходит для данной профессии.";

        return $this->ai->chat($prompt);
    }
}