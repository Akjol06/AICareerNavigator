<?php

namespace App\Controller;

use App\DTO\ResumeInput;
use App\Service\ResumeService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

class ResumeController extends AbstractController
{
    public function __construct(private ResumeService $service) {}

    public function __invoke(Request $request): JsonResponse
    {
        $data = json_decode($request->getContent(), true);

        if ($data === null) {
            return $this->json(['error' => 'Invalid JSON in request body'], 400);
        }

        if (!isset($data['resumeText'])) {
            return $this->json(['error' => 'Missing resumeText field'], 400);
        }

        $resumeText = $data['resumeText'];
        if (is_array($resumeText)) {
            $resumeText = implode(', ', $resumeText);
        } elseif (!is_string($resumeText)) {
            return $this->json(['error' => 'resumeText must be a string or array of strings'], 400);
        }

        if (empty(trim($resumeText))) {
            return $this->json(['error' => 'resumeText cannot be empty'], 400);
        }

        try {
            $result = $this->service->improve($resumeText);
            return $this->json($result);
        } catch (\Exception $e) {
            return $this->json(['error' => 'An error occurred while processing your request'], 500);
        }
    }
}