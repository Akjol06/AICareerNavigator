<?php

namespace App\Controller;

use App\Service\Career\CareerTestService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;


class CareerTestController extends AbstractController
{
    public function __construct(private CareerTestService $service) {}

    public function __invoke(Request $request): JsonResponse
    {
        $data = json_decode($request->getContent(), true);

        if ($data === null) {
            return $this->json(['error' => 'Invalid JSON in request body'], 400);
        }

        if (!isset($data['answers']) || !is_array($data['answers'])) {
            return $this->json(['error' => 'Missing or invalid answers field. Answers must be an array'], 400);
        }

        if (empty($data['answers'])) {
            return $this->json(['error' => 'Answers array cannot be empty'], 400);
        }

        try {
            $result = $this->service->analyze($data['answers']);
            return $this->json($result);
        } catch (\Exception $e) {
            return $this->json(['error' => 'An error occurred while processing your request'], 500);
        }
    }
}