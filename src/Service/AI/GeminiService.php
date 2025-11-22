<?php

namespace App\Service\AI;

use Symfony\Contracts\HttpClient\HttpClientInterface;

class GeminiService
{
    public function __construct(
        private HttpClientInterface $client,
        private string $apiKey
    ) {}

    public function chat(string $text): string
    {
        if (empty(trim($text))) {
            throw new \InvalidArgumentException('Text cannot be empty');
        }

        $url = 'https://generativelanguage.googleapis.com/v1beta/models/gemini-2.0-flash:generateContent?key=' . $this->apiKey;

        try {
            $response = $this->client->request('POST', $url, [
                'json' => [
                    'contents' => [
                        [
                            'parts' => [
                                ['text' => $text]
                            ]
                        ]
                    ]
                ],
                'timeout' => 30
            ]);

            $statusCode = $response->getStatusCode();
            if ($statusCode !== 200) {
                throw new \RuntimeException("Gemini API returned status code: {$statusCode}");
            }

            // ⚡ Важно: преобразуем ответ в массив
            $data = $response->toArray();

            // Проверяем наличие данных
            if (!isset($data['candidates']) || empty($data['candidates'])) {
                throw new \RuntimeException('No candidates in Gemini API response');
            }

            $candidate = $data['candidates'][0];
            if (!isset($candidate['content']['parts'][0]['text'])) {
                throw new \RuntimeException('No text content in Gemini API response');
            }

            // ⚡ Берём первый candidate и первый part
            return $candidate['content']['parts'][0]['text'];
        } catch (\Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface $e) {
            throw new \RuntimeException('Failed to connect to Gemini API: ' . $e->getMessage(), 0, $e);
        } catch (\Symfony\Contracts\HttpClient\Exception\HttpExceptionInterface $e) {
            throw new \RuntimeException('Gemini API HTTP error: ' . $e->getMessage(), 0, $e);
        }
    }
}