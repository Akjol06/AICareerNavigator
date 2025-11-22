<?php

namespace App\Service\Career;

use App\Service\AI\GeminiService;

class CareerTestService
{
    public function __construct(private GeminiService $ai) {}

    public function analyze(array $answers): array
    {
        if (empty($answers)) {
            throw new \InvalidArgumentException('Answers array cannot be empty');
        }

        // Фильтруем пустые значения
        $answers = array_filter(array_map('trim', $answers), fn($answer) => !empty($answer));
        
        if (empty($answers)) {
            throw new \InvalidArgumentException('Answers array contains only empty values');
        }

        $prompt = "Ты — профессиональный карьерный консультант.\n\n"
                . "Проанализируй ответы пользователя и выдай результат строго в JSON.\n\n"
                . "Ответы пользователя:\n"
                . json_encode($answers, JSON_UNESCAPED_UNICODE) . "\n\n"
                . "Формат ответа (строго JSON):\n"
                . "{\n"
                . "  \"personality_type\": \"тип личности пользователя\",\n"
                . "  \"strengths\": [\"сильная сторона 1\", \"сильная сторона 2\", ...],\n"
                . "  \"weaknesses\": [\"слабая сторона 1\", \"слабая сторона 2\", ...],\n"
                . "  \"suitable_careers\": [\"профессия 1\", \"профессия 2\", ...],\n"
                . "  \"skills_to_improve\": [\"навык 1\", \"навык 2\", ...],\n"
                . "  \"summary\": \"краткое описание пользователя и его потенциала\"\n"
                . "}\n\n"
                . "Не задавай вопросы. Не проси уточнений. Просто анализируй данные.";

        $response = $this->ai->chat($prompt);

        $response = trim($response);
        $response = preg_replace('/^```json\s*/i', '', $response);
        $response = preg_replace('/\s*```$/i', '', $response);
        
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