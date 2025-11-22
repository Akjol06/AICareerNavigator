<?php

namespace App\Service\Career;

use App\Service\AI\GeminiService;

class CareerPlanService
{
    public function __construct(private GeminiService $ai) {}

    public function create(string $goal, array $skills): array
    {
        if (empty(trim($goal))) {
            throw new \InvalidArgumentException('Goal cannot be empty');
        }

        if (empty($skills)) {
            throw new \InvalidArgumentException('Skills array cannot be empty');
        }

        // Фильтруем пустые значения
        $skills = array_filter(array_map('trim', $skills), fn($skill) => !empty($skill));
        
        if (empty($skills)) {
            throw new \InvalidArgumentException('Skills array contains only empty values');
        }

        $prompt = "Ты — профессиональный карьерный консультант.\n"
                . "Создай подробный карьерный план для цели '{$goal}' с навыками: " 
                . json_encode($skills, JSON_UNESCAPED_UNICODE) . ".\n\n"
                . "Разбей по шагам:\n"
                . "- что изучать (steps) - массив строк с конкретными шагами\n"
                . "- примерное время (estimated_time_months) - число месяцев\n"
                . "- какие курсы пройти (suggested_courses) - массив названий курсов\n\n"
                . "Ответ строго в формате JSON:\n"
                . "{\n"
                . "  \"steps\": [...],\n"
                . "  \"estimated_time_months\": ...,\n"
                . "  \"suggested_courses\": [...]\n"
                . "}\n\n"
                . "Не добавляй ничего лишнего, только JSON.";

        $response = $this->ai->chat($prompt);

        // Убираем ```json если AI их добавил
        $response = trim($response);
        $response = preg_replace('/^```json\s*/i', '', $response);
        $response = preg_replace('/\s*```$/i', '', $response);

        // Преобразуем в массив
        $data = json_decode($response, true);

        if (!$data || json_last_error() !== JSON_ERROR_NONE) {
            return [
                'error' => 'AI returned invalid JSON',
                'raw' => $response,
                'json_error' => json_last_error_msg()
            ];
        }

        return $data;
    }
}