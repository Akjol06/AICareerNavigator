<?php

namespace App\DTO;

use ApiPlatform\Metadata\ApiProperty;
use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\Post;
use App\Controller\ResumeController;
use Symfony\Component\Serializer\Annotation\Groups;

#[ApiResource(
    operations: [
        new Post(uriTemplate: '/api/resume/improve', name: 'resume_improve', controller: ResumeController::class, denormalizationContext: ['groups' => ['resume:input']])
    ]
)]
class ResumeInput
{
    #[ApiProperty(openapiContext: ["example" => ["Я разработчик. Занимаюсь PHP и Symfony. Ищу работу."]])]
    #[Groups("resume:input")]
    public string $resumeText = '';
}