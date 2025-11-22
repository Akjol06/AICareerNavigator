<?php

namespace App\DTO;

use ApiPlatform\Metadata\ApiProperty;
use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\Post;
use App\Controller\CollegeController;
use Symfony\Component\Serializer\Annotation\Groups;


#[ApiResource(
    operations: [
        new Post(uriTemplate: '/api/colleges/search', name: 'colleges_search', controller: CollegeController::class, denormalizationContext: ['groups' => ['college:input']])
    ]
)]
class CollegeSearchInput
{
    #[ApiProperty(openapiContext: ["example" => ["Software Engineer"]])]
    #[Groups(['college:input'])]
    public string $profession = '';
}