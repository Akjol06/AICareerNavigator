<?php

namespace App\Service\Career;

use App\Service\AI\GeminiService;

class SkillsMatcherService
{
    public function __construct(private GeminiService $ai) {}

    public function match(array $skills): string
    {
        if (empty($skills)) {
            throw new \InvalidArgumentException('Skills array cannot be empty');
        }

        // Фильтруем пустые значения и обрезаем пробелы
        $skills = array_filter(array_map('trim', $skills), fn($skill) => !empty($skill));
        
        if (empty($skills)) {
            throw new \InvalidArgumentException('Skills array contains only empty values');
        }

        $skillsList = implode(", ", $skills);
        $prompt = "Подбери профессии по навыкам: {$skillsList}.\n"
                . "Объясни, почему каждая подходит.\n"
                . "Выведи текст с нумерацией и подробными пояснениями.\n"
                . "Для каждой профессии укажи:\n"
                . "- Название профессии\n"
                . "- Почему она подходит для данных навыков\n"
                . "- Какие дополнительные навыки могут потребоваться";

        $response = $this->ai->chat($prompt);

        // Убираем возможные ``` блоки
        $response = trim($response);
        $response = preg_replace('/^```(json|txt|markdown)?\s*/i', '', $response);
        $response = preg_replace('/\s*```$/i', '', $response);

        // Убираем лишние пробелы в начале строк
        $response = preg_replace('/^\s+/m', '', $response);

        return $response;
    }
}