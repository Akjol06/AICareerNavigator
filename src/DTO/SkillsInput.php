<?php

namespace App\DTO;

use ApiPlatform\Metadata\ApiProperty;
use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\Post;
use App\Controller\SkillsMatcherController;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;

#[ApiResource(
    operations: [
        new Post(uriTemplate: '/api/skills-match', name: 'skills_match', controller: SkillsMatcherController::class, denormalizationContext: ['groups' => ['skills:input']])
    ]
)]
class SkillsInput
{
    #[Assert\NotBlank(message: "Skills cannot be empty")]
    #[Assert\Type('array', message: "Skills must be an array")]
    #[ApiProperty(openapiContext: ['example' => ["Python", "Data Analysis", "Machine Learning"]])]
    #[Groups("skills:input")]
    public array $skills = [];
}