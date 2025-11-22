<?php

namespace App\Controller;

use App\Service\CollegeSearchService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;

class CollegeController extends AbstractController
{
    public function __construct(private CollegeSearchService $service) {}

    public function __invoke(Request $request): JsonResponse
    {
        $data = json_decode($request->getContent(), true);

        if ($data === null) {
            return $this->json(['error' => 'Invalid JSON in request body'], 400);
        }

        if (!isset($data['profession'])) {
            return $this->json(['error' => 'Missing profession field'], 400);
        }

        $profession = $data['profession'];
        if (!is_string($profession) || empty(trim($profession))) {
            return $this->json(['error' => 'profession must be a non-empty string'], 400);
        }

        try {
            $result = $this->service->find(trim($profession));
            return $this->json(['recommendations' => $result]);
        } catch (\Exception $e) {
            return $this->json(['error' => 'An error occurred while processing your request'], 500);
        }
    }
}