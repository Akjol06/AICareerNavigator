<?php

namespace App\Service;

use App\Service\AI\GeminiService;

class ResumeService
{
    public function __construct(private GeminiService $ai) {}

    public function improve(string $resume): array
    {
        if (empty(trim($resume))) {
            throw new \InvalidArgumentException('Resume text cannot be empty');
        }

        $prompt = "Ты — эксперт по составлению профессиональных резюме.\n\n"
                . "Проанализируй текст резюме ниже и верни улучшенный вариант.\n"
                . "Перепиши так, чтобы звучало более профессионально, структурировано и убедительно.\n\n"
                . "Текст резюме:\n"
                . "\"{$resume}\"\n\n"
                . "Ответ верни строго в JSON:\n"
                . "{\n"
                . "  \"improved_text\": \"улучшенный текст резюме\",\n"
                . "  \"recommendations\": [\n"
                . "    \"рекомендация 1\",\n"
                . "    \"рекомендация 2\",\n"
                . "    \"рекомендация 3\"\n"
                . "  ],\n"
                . "  \"keywords\": [\n"
                . "    \"ключевое слово 1\",\n"
                . "    \"ключевое слово 2\"\n"
                . "  ]\n"
                . "}\n\n"
                . "Не добавляй пояснений. Только JSON.";

        $raw = $this->ai->chat($prompt);

        // Удаляем Markdown
        $raw = trim($raw);
        $raw = preg_replace('/^```json\s*/i', '', $raw);
        $raw = preg_replace('/\s*```$/i', '', $raw);

        $data = json_decode($raw, true);

        if (!$data || json_last_error() !== JSON_ERROR_NONE) {
            return [
                'error' => 'AI returned invalid JSON',
                'raw' => $raw,
                'json_error' => json_last_error_msg()
            ];
        }

        return $data;
    }
}