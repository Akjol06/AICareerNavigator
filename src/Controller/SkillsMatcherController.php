<?php

namespace App\Controller;

use App\Service\Career\SkillsMatcherService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;

class SkillsMatcherController extends AbstractController
{
    public function __construct(private SkillsMatcherService $service) {}

    public function __invoke(Request $request): JsonResponse
    {
        $data = json_decode($request->getContent(), true);

        if ($data === null) {
            return $this->json(['error' => 'Invalid JSON in request body'], 400);
        }

        if (!isset($data['skills']) || !is_array($data['skills'])) {
            return $this->json(['error' => 'Missing or invalid skills field. Skills must be an array'], 400);
        }

        if (empty($data['skills'])) {
            return $this->json(['error' => 'Skills array cannot be empty'], 400);
        }

        try {
            $result = $this->service->match($data['skills']);
            return $this->json(['recommendations' => $result]);
        } catch (\Exception $e) {
            return $this->json(['error' => 'An error occurred while processing your request'], 500);
        }
    }
}