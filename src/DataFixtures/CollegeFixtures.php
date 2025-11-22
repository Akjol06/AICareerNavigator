<?php

namespace App\DataFixtures;

use App\Entity\College;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class CollegeFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $colleges = [
            [
                'name' => 'Kyrgyz State Technical University',
                'city' => 'Bishkek',
                'specializations' => ['Computer Science', 'Software Engineering', 'Information Systems']
            ],
            [
                'name' => 'American University of Central Asia',
                'city' => 'Bishkek',
                'specializations' => ['Business', 'Computer Science', 'Economics']
            ],
            [
                'name' => 'Bishkek Humanities University',
                'city' => 'Bishkek',
                'specializations' => ['Design', 'Journalism']
            ],
            [
                'name' => 'Osh State University',
                'city' => 'Osh',
                'specializations' => ['Engineering', 'Computer Science', 'Law']
            ],
            [
                'name' => 'Kyrgyz-Turkish Manas University',
                'city' => 'Bishkek',
                'specializations' => ['Engineering', 'International Relations', 'Computer Science']
            ],
            [
                'name' => 'Kyrgyz State Medical Academy',
                'city' => 'Bishkek',
                'specializations' => ['Medicine', 'Pharmacy', 'Dentistry']
            ],
            [
                'name' => 'Kyrgyz National University',
                'city' => 'Bishkek',
                'specializations' => ['Law', 'Economics', 'Sociology']
            ],
            [
                'name' => 'Jalal-Abad State University',
                'city' => 'Jalal-Abad',
                'specializations' => ['Education', 'Engineering', 'Economics']
            ],
            [
                'name' => 'Naryn State University',
                'city' => 'Naryn',
                'specializations' => ['Agriculture', 'Education', 'Tourism']
            ],
            [
                'name' => 'Issyk-Kul State University',
                'city' => 'Karakol',
                'specializations' => ['Tourism', 'Economics', 'History']
            ]
        ];

        foreach ($colleges as $c) {
            $college = new College();
            $college->setName($c['name']);
            $college->setCity($c['city']);
            $college->setSpecializations($c['specializations']);
            $manager->persist($college);
        }

        $manager->flush();
    }
}