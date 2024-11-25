<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class LessonSeeder extends Seeder
{
    public function run(): void
    {
        // Total lessons to be generated
        $totalLessons = 100;

        // Courses IDs range from 1 to 10
        $courseIds = range(1, 10);

        // Shuffle the courses to randomize the assignment of lessons
        shuffle($courseIds);

        // Generate lessons and distribute them randomly across the courses
        $lessons = [];
        $remainingLessons = $totalLessons;

        // Randomly assign the number of lessons to each course
        foreach ($courseIds as $courseId) {
            // Randomly decide how many lessons each course will have
            $numLessonsForCourse = rand(5, 15); // Each course will have between 5 and 15 lessons
            $numLessonsForCourse = min($numLessonsForCourse, $remainingLessons); // Ensure we don't exceed the total lessons

            // Generate lessons for the current course
            for ($lessonNumber = 1; $lessonNumber <= $numLessonsForCourse; $lessonNumber++) {
                $lessons[] = [
                    'course_id' => $courseId,
                    'title' => "Lesson " . (count($lessons) + 1),
                    'description' => "This is lesson " . (count($lessons) + 1) . " of course $courseId",
                    'duration' => rand(10, 30), // Random duration between 10 and 30 minutes
                    'video_url' => "https://www.youtube.com/watch?v=video" . (count($lessons) + 1),
                    'thumbnail' => "thumbnail" . (count($lessons) + 1) . ".jpg"
                ];
            }

            $remainingLessons -= $numLessonsForCourse;
            if ($remainingLessons <= 0) {
                break;
            }
        }

        // Insert the lessons into the database
        DB::table('lessons')->insert($lessons);
    }
}
