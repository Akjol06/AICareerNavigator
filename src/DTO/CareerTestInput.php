<?php

namespace App\DTO;

use ApiPlatform\Metadata\ApiProperty;
use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\Post;
use App\Controller\CareerTestController;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;

#[ApiResource(
    operations: [
        new Post(uriTemplate: '/ai/test', name: 'ai_test', controller: CareerTestController::class, denormalizationContext: ['groups' => ['test:input']],        )
    ]
)]
class CareerTestInput
{
    #[Assert\NotBlank(message: "Answers cannot be empty")]
    #[Assert\Type('array', message: "Answers must be an array")]
    #[Assert\All([
        new Assert\Type('string')
    ])]
    #[ApiProperty(openapiContext: ["example" => [
            "Мне нравится работать с цифрами и логикой",
            "Я люблю креативные задачи",
            "Предпочитаю работать в команде",
            "Я хорошо разбираюсь в технологиях"
        ]]
    )]
    #[Groups("test:input")]
    public array $answers = [];
}