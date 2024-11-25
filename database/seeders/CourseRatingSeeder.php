<?php

namespace Database\Seeders;

use App\Models\Course;
use App\Models\CourseRating;
use App\Models\User;
use Illuminate\Database\Seeder;

class CourseRatingSeeder extends Seeder
{
    public function run(): void
    {
        $users = User::all();
        $courses = Course::all();

        for ($i = 0; $i < 1000; $i++) {
            $rating = rand(1, 5);
            $user = $users->random();
            $course = $courses->random();

            // Check if the user has already rated the course
            $existingRating = CourseRating::where('user_id', $user->id)
                ->where('course_id', $course->id)
                ->first();

            if (!$existingRating) {
                CourseRating::create([
                    'user_id' => $user->id,
                    'course_id' => $course->id,
                    'rating' => $rating,
                ]);
            }
        }
    }
}
