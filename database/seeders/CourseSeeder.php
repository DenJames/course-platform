<?php

namespace Database\Seeders;

use App\Models\Course;
use Illuminate\Database\Seeder;

class CourseSeeder extends Seeder
{
    public function run(): void
    {
        $courses = [
            [
                'title' => 'Laravel 11',
                'description' => 'Lær Laravel 11 fra bunden',
                'price' => 100,
                'image' => 'https://cdn.pixabay.com/photo/2016/05/27/08/51/mobile-phone-1419275_960_720.jpg',
            ],
            [
                'title' => 'React JS',
                'description' => 'Lær React JS fra bunden',
                'price' => 200,
                'image' => 'https://cdn.pixabay.com/photo/2016/05/27/08/51/mobile-phone-1419275_960_720.jpg',
            ],
            [
                'title' => 'Vue JS',
                'description' => 'Lær Vue JS fra bunden',
                'price' => 150,
                'image' => 'https://cdn.pixabay.com/photo/2016/05/27/08/51/mobile-phone-1419275_960_720.jpg',
            ],
            [
                'title' => 'Angular',
                'description' => 'Lær Angular fra bunden',
                'price' => 250,
                'image' => 'https://cdn.pixabay.com/photo/2016/05/27/08/51/mobile-phone-1419275_960_720.jpg',
            ],
            [
                'title' => 'Node JS',
                'description' => 'Lær Node JS fra bunden',
                'price' => 300,
                'image' => 'https://cdn.pixabay.com/photo/2016/05/27/08/51/mobile-phone-1419275_960_720.jpg',
            ],
            [
                'title' => 'Python',
                'description' => 'Lær Python fra bunden',
                'price' => 350,
                'image' => 'https://cdn.pixabay.com/photo/2016/05/27/08/51/mobile-phone-1419275_960_720.jpg',
            ],
            [
                'title' => 'Django',
                'description' => 'Lær Django fra bunden',
                'price' => 400,
                'image' => 'https://cdn.pixabay.com/photo/2016/05/27/08/51/mobile-phone-1419275_960_720.jpg',
            ],
            [
                'title' => 'Flask',
                'description' => 'Lær Flask fra bunden',
                'price' => 450,
                'image' => 'https://cdn.pixabay.com/photo/2016/05/27/08/51/mobile-phone-1419275_960_720.jpg',
            ],
            [
                'title' => 'Java',
                'description' => 'Lær Java fra bunden',
                'price' => 500,
                'image' => 'https://cdn.pixabay.com/photo/2016/05/27/08/51/mobile-phone-1419275_960_720.jpg',
            ],
            [
                'title' => 'Spring Boot',
                'description' => 'Lær Spring Boot fra bunden',
                'price' => 550,
                'image' => 'https://cdn.pixabay.com/photo/2016/05/27/08/51/mobile-phone-1419275_960_720.jpg',
            ]
        ];

        foreach ($courses as $course) {
            Course::factory()->create($course);
        }
    }
}
