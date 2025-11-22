<?php

namespace App\DTO;

use ApiPlatform\Metadata\ApiProperty;
use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\Post;
use App\Controller\CareerPlanController;
use Symfony\Component\Serializer\Annotation\Groups;

#[ApiResource(
    operations: [
        new Post(uriTemplate: '/api/career-plan', name: 'career_plan', controller: CareerPlanController::class, denormalizationContext: ['groups' => ['career:input']])
    ]
)]
class CareerPlanInput
{
    #[ApiProperty(openapiContext: ["example" => ["Стать Full Stack разработчиком"]])]
    #[Groups("career:input")]
    public string $goal;

    #[ApiProperty(openapiContext: ["example" => ["HTML", "CSS", "JavaScript", "PHP", "Symfony"]])]
    #[Groups("career:input")]
    public array $skills = [];
}