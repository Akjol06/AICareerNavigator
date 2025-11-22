<?php

namespace App\Controller;

use App\Service\Career\CareerPlanService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;

class CareerPlanController extends AbstractController
{
    public function __construct(private CareerPlanService $service) {}

    public function __invoke(Request $request): JsonResponse
    {
        $data = json_decode($request->getContent(), true);

        if ($data === null) {
            return $this->json(['error' => 'Invalid JSON in request body'], 400);
        }

        if (!isset($data['goal'])) {
            return $this->json(['error' => 'Missing goal field'], 400);
        }

        if (!isset($data['skills']) || !is_array($data['skills'])) {
            return $this->json(['error' => 'Missing or invalid skills field. Skills must be an array'], 400);
        }

        if (empty($data['skills'])) {
            return $this->json(['error' => 'Skills array cannot be empty'], 400);
        }

        $goal = $data['goal'];
        if (is_array($goal)) {
            $goal = implode(', ', $goal);
        } elseif (!is_string($goal)) {
            return $this->json(['error' => 'Goal must be a string or array of strings'], 400);
        }

        if (empty(trim($goal))) {
            return $this->json(['error' => 'Goal cannot be empty'], 400);
        }

        try {
            $result = $this->service->create(trim($goal), $data['skills']);
            return $this->json($result);
        } catch (\Exception $e) {
            return $this->json(['error' => 'An error occurred while processing your request'], 500);
        }
    }
}